<?php

declare(strict_types=1);

date_default_timezone_set('Africa/Kampala');
session_start();

// 1. PROJECT CONSTANTS
define('VIEW_PATH', dirname(__DIR__) . '/resources/views');
define('PUBLIC_PATH', dirname(__DIR__) . '/public');

// 2. DATABASE CONNECTION
$dbPath = dirname(__DIR__) . '/maf.db';
$db = new PDO('sqlite:' . $dbPath);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// CRITICAL: Using FETCH_OBJ so templates use $item->name
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

init_db($db);
seed_if_empty($db);

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// 3. ROUTING LOGIC
if ($path === '/' && $method === 'GET') {
    handle_home($db);
} elseif ($path === '/how-it-works') {
    render('how');
} elseif ($path === '/about') {
    render('about');
} elseif ($path === '/contact') {
    if ($method === 'POST') {
        flash('Thanks! Message received.', 'success');
        redirect('/contact');
    }
    render('contact');
} elseif ($path === '/login') {
    if ($method === 'POST') handle_login($db);
    render('auth/login');
} elseif ($path === '/register') {
    if ($method === 'POST') handle_register($db);
    render('auth/register');
} elseif ($path === '/logout') {
    session_destroy();
    session_start();
    flash('Logged out successfully.', 'info');
    redirect('/');
} elseif ($path === '/pharmacist' && $method === 'GET') {
    handle_pharmacist_dashboard($db);
} elseif ($path === '/pharmacist/add' && $method === 'POST') {
    handle_inventory_add($db);
} elseif ($path === '/pharmacist/requests' && $method === 'GET') {
    handle_pharmacist_requests($db);
} elseif ($path === '/admin' && $method === 'GET') {
    handle_admin_dashboard($db);
} elseif (preg_match('#^/admin/pharmacies/(\d+)/(approve|reject)$#', $path, $m)) {
    handle_moderate_pharmacy($db, (int)$m[1], $m[2]);
} elseif (preg_match('#^/reserve/(\d+)$#', $path, $m) && $method === 'POST') {
    handle_reserve($db, (int)$m[1]);
} elseif ($path === '/requests' && $method === 'GET') {
    handle_patient_requests($db);
} else {
    http_response_code(404);
    echo "404 - Page Not Found";
}

// --- 4. CORE ENGINE FUNCTIONS ---

function render(string $template, array $data = []): void
{
    $alerts = flashes();
    $currentUser = current_user();
    extract($data);
    ob_start();
    $templatePath = VIEW_PATH . "/{$template}.blade.php";
    if (file_exists($templatePath)) {
        include $templatePath;
    } else {
        echo "Template missing: {$template}";
    }
    $content = ob_get_clean();
    include VIEW_PATH . '/layouts/app.blade.php';
    exit;
}

function current_user(): ?object
{
    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) return null;
    global $db;
    $stmt = $db->prepare('SELECT * FROM users WHERE id = :id');
    $stmt->execute([':id' => $userId]);
    return $stmt->fetch() ?: null;
}

function require_login(?string $role = null): object
{
    $user = current_user();
    if (!$user) {
        flash('Please login to continue.', 'warning');
        redirect('/login');
    }
    if ($role && $user->role !== $role) {
        flash('Access denied. Admin privileges required.', 'danger');
        redirect('/');
    }
    return $user;
}

function flash(string $message, string $category = 'info'): void
{
    $_SESSION['alerts'][] = ['category' => $category, 'message' => $message];
}

function flashes(): array
{
    $msgs = $_SESSION['alerts'] ?? [];
    unset($_SESSION['alerts']);
    return $msgs;
}

function redirect(string $path): void
{
    header("Location: {$path}");
    exit;
}

// --- 5. CONTROLLER HANDLERS (ADMIN CORRECTED) ---

function handle_home(PDO $db): void
{
    $query = trim($_GET['q'] ?? '');
    $results = [];
    if ($query !== '') {
        $stmt = $db->prepare("
            SELECT pm.id, pm.price, pm.stock_status, pm.quantity,
                   m.name AS medicine_name, p.name AS pharmacy_name, p.location AS pharmacy_location
            FROM pharmacy_medicines pm
            JOIN medicines m ON pm.medicine_id = m.id
            JOIN pharmacies p ON pm.pharmacy_id = p.id
            WHERE m.name LIKE :q AND p.status = 'approved'
        ");
        $stmt->execute([':q' => "%{$query}%"]);
        $results = $stmt->fetchAll();
    }
    render('home', compact('query', 'results'));
}

function handle_login(PDO $db): void
{
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $stmt = $db->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user->password)) {
        $_SESSION['user_id'] = $user->id;
        flash("Welcome, {$user->name}!", 'success');

        // Admin Correction: Redirect to specific dashboards
        if ($user->role === 'admin') redirect('/admin');
        if ($user->role === 'pharmacist') redirect('/pharmacist');
        redirect('/');
    }
    flash('Invalid email or password.', 'danger');
    redirect('/login');
}

function handle_register(PDO $db): void
{
    $name = trim($_POST['name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'patient';

    if (empty($name) || empty($email) || empty($password)) {
        flash('All fields are required.', 'danger');
        redirect('/register');
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $db->prepare('INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)');
    try {
        $stmt->execute([$name, $email, $hashed, $role]);
        flash('Registration successful! Please login.', 'success');
        redirect('/login');
    } catch (PDOException $e) {
        flash('Email already exists.', 'danger');
        redirect('/register');
    }
}

function handle_admin_dashboard(PDO $db): void
{
    require_login('admin');
    $pending = $db->query("SELECT * FROM pharmacies WHERE status = 'pending'")->fetchAll();
    $stats = [
        'users' => $db->query('SELECT COUNT(*) FROM users')->fetchColumn(),
        'pharmacies' => $db->query('SELECT COUNT(*) FROM pharmacies WHERE status = "approved"')->fetchColumn(),
        'medicines' => $db->query('SELECT COUNT(*) FROM medicines')->fetchColumn(),
        'reservations' => $db->query('SELECT COUNT(*) FROM reservations')->fetchColumn(),
    ];
    render('admin_dashboard', compact('pending', 'stats'));
}

function handle_moderate_pharmacy(PDO $db, int $id, string $action): void
{
    require_login('admin');
    $status = ($action === 'approve') ? 'approved' : 'rejected';
    $stmt = $db->prepare('UPDATE pharmacies SET status = ? WHERE id = ?');
    $stmt->execute([$status, $id]);
    flash("Pharmacy updated to {$status}.", 'info');
    redirect('/admin');
}

function handle_pharmacist_dashboard(PDO $db): void
{
    require_login('pharmacist');
    $user = current_user();
    $stmt = $db->prepare("SELECT * FROM pharmacies WHERE owner_id = ?");
    $stmt->execute([$user->id]);
    $pharmacy = $stmt->fetch();
    if (!$pharmacy) {
        flash('No pharmacy associated with your account.', 'warning');
        redirect('/');
    }
    $stmt = $db->prepare("
        SELECT pm.*, m.name AS medicine_name
        FROM pharmacy_medicines pm
        JOIN medicines m ON pm.medicine_id = m.id
        WHERE pm.pharmacy_id = ?
    ");
    $stmt->execute([$pharmacy->id]);
    $inventory = $stmt->fetchAll();
    render('pharmacist_dashboard', compact('pharmacy', 'inventory'));
}

function handle_inventory_add(PDO $db): void
{
    require_login('pharmacist');
    $user = current_user();
    $stmt = $db->prepare("SELECT id FROM pharmacies WHERE owner_id = ?");
    $stmt->execute([$user->id]);
    $pharmacy_id = $stmt->fetchColumn();
    if (!$pharmacy_id) {
        flash('No pharmacy found.', 'danger');
        redirect('/pharmacist');
    }
    $medicine_name = trim($_POST['medicine_name'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $quantity = (int)($_POST['quantity'] ?? 0);
    $stock_status = $_POST['stock_status'] ?? 'available';

    if (empty($medicine_name) || $price <= 0 || $quantity < 0) {
        flash('Invalid input.', 'danger');
        redirect('/pharmacist');
    }

    // Check if medicine exists, else add
    $stmt = $db->prepare("SELECT id FROM medicines WHERE name = ?");
    $stmt->execute([$medicine_name]);
    $medicine_id = $stmt->fetchColumn();
    if (!$medicine_id) {
        $stmt = $db->prepare("INSERT INTO medicines (name) VALUES (?)");
        $stmt->execute([$medicine_name]);
        $medicine_id = $db->lastInsertId();
    }

    $stmt = $db->prepare("INSERT INTO pharmacy_medicines (pharmacy_id, medicine_id, price, stock_status, quantity) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$pharmacy_id, $medicine_id, $price, $stock_status, $quantity]);
    flash('Medicine added to inventory.', 'success');
    redirect('/pharmacist');
}

function handle_pharmacist_requests(PDO $db): void
{
    require_login('pharmacist');
    $user = current_user();
    $stmt = $db->prepare("SELECT id FROM pharmacies WHERE owner_id = ?");
    $stmt->execute([$user->id]);
    $pharmacy_id = $stmt->fetchColumn();
    if (!$pharmacy_id) {
        flash('No pharmacy found.', 'warning');
        redirect('/pharmacist');
    }
    $stmt = $db->prepare("
        SELECT r.*, u.name AS user_name, m.name AS medicine_name
        FROM reservations r
        JOIN users u ON r.user_id = u.id
        JOIN medicines m ON r.medicine_id = m.id
        WHERE r.pharmacy_id = ?
        ORDER BY r.created_at DESC
    ");
    $stmt->execute([$pharmacy_id]);
    $requests = $stmt->fetchAll();
    render('pharmacist_requests', compact('requests'));
}

function handle_reserve(PDO $db, int $pm_id): void
{
    require_login();
    $user = current_user();
    $stmt = $db->prepare("SELECT * FROM pharmacy_medicines WHERE id = ?");
    $stmt->execute([$pm_id]);
    $pm = $stmt->fetch();
    if (!$pm) {
        flash('Medicine not found.', 'danger');
        redirect('/');
    }
    $note = trim($_POST['note'] ?? '');
    $stmt = $db->prepare("INSERT INTO reservations (user_id, pharmacy_id, medicine_id, status, note, created_at) VALUES (?, ?, ?, 'pending', ?, datetime('now'))");
    $stmt->execute([$user->id, $pm->pharmacy_id, $pm->medicine_id, $note]);
    flash('Reservation request submitted.', 'success');
    redirect('/');
}

function handle_patient_requests(PDO $db): void
{
    require_login();
    $user = current_user();
    $stmt = $db->prepare("
        SELECT r.*, p.name AS pharmacy_name, m.name AS medicine_name
        FROM reservations r
        JOIN pharmacies p ON r.pharmacy_id = p.id
        JOIN medicines m ON r.medicine_id = m.id
        WHERE r.user_id = ?
        ORDER BY r.created_at DESC
    ");
    $stmt->execute([$user->id]);
    $requests = $stmt->fetchAll();
    render('patient_requests', compact('requests'));
}

// --- 6. DATABASE SETUP ---

function init_db(PDO $db): void
{
    $db->exec("CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY, name TEXT, email TEXT UNIQUE, password TEXT, role TEXT)");
    $db->exec("CREATE TABLE IF NOT EXISTS pharmacies (id INTEGER PRIMARY KEY, name TEXT, location TEXT, license_number TEXT, phone TEXT, status TEXT, owner_id INTEGER)");
    $db->exec("CREATE TABLE IF NOT EXISTS medicines (id INTEGER PRIMARY KEY, name TEXT, category TEXT)");
    $db->exec("CREATE TABLE IF NOT EXISTS pharmacy_medicines (id INTEGER PRIMARY KEY, pharmacy_id INTEGER, medicine_id INTEGER, price REAL, stock_status TEXT, quantity INTEGER)");
    $db->exec("CREATE TABLE IF NOT EXISTS reservations (id INTEGER PRIMARY KEY, user_id INTEGER, pharmacy_id INTEGER, medicine_id INTEGER, status TEXT, note TEXT, created_at TEXT)");
}

function seed_if_empty(PDO $db): void
{
    // We check for the email specifically to see if the admin exists
    $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute(['admin@maf.com']);
    $exists = $stmt->fetchColumn();

    if (!$exists) {
        // Corrected: password_hash instead of password()
        // Corrected: 'password' column name to match pgAdmin
        $hashed = password_hash('admin@2026', PASSWORD_DEFAULT);

        $db->prepare("INSERT INTO users (name, email, role, password) VALUES (?,?,?,?)")
            ->execute(['administrator', 'admin@maf.com', 'admin', $hashed]);
    }
}
