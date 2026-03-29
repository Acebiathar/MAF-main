<?php
declare(strict_types=1);

date_default_timezone_set('Africa/Kampala');
session_start();

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
if (PHP_SAPI === 'cli-server') {
    $static = __DIR__ . $path;
    if ($path !== '/' && is_file($static)) {
        return false;
    }
}

$dbPath = __DIR__ . '/maf.db';
$db = new PDO('sqlite:' . $dbPath);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

init_db($db);
seed_if_empty($db);

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// ROUTES
if ($path === '/' && $method === 'GET') {
    handle_home($db);
} elseif ($path === '/how-it-works') {
    render('how');
} elseif ($path === '/about') {
    render('about');
} elseif ($path === '/contact') {
    if ($method === 'POST') {
        flash('Thanks! We received your message and will reply soon.', 'success');
        redirect('/contact');
    }
    render('contact');
} elseif ($path === '/login') {
    if ($method === 'POST') {
        handle_login($db);
    }
    render('auth/login');
} elseif ($path === '/register') {
    if ($method === 'POST') {
        handle_register($db);
    }
    render('auth/register');
} elseif ($path === '/logout') {
    session_unset();
    session_destroy();
    session_start();
    flash('Logged out.', 'info');
    redirect('/');
} elseif ($path === '/pharmacist' && $method === 'GET') {
    handle_pharmacist_dashboard($db);
} elseif ($path === '/pharmacist/add' && $method === 'POST') {
    handle_inventory_add($db);
} elseif (preg_match('#^/reserve/(\d+)$#', $path, $m) && $method === 'POST') {
    handle_reserve($db, (int)$m[1]);
} elseif ($path === '/requests' && $method === 'GET') {
    handle_patient_requests($db);
} elseif ($path === '/pharmacist/requests' && $method === 'GET') {
    handle_pharmacist_requests($db);
} elseif (preg_match('#^/pharmacist/requests/(\d+)/(confirm|decline)$#', $path, $m) && $method === 'POST') {
    handle_reservation_update($db, (int)$m[1], $m[2]);
} elseif ($path === '/admin' && $method === 'GET') {
    handle_admin_dashboard($db);
} elseif (preg_match('#^/admin/pharmacies/(\d+)/(approve|reject)$#', $path, $m) && $method === 'GET') {
    handle_moderate_pharmacy($db, (int)$m[1], $m[2]);
} else {
    http_response_code(404);
    echo 'Not found';
}

// FUNCTIONS

function render(string $template, array $data = []): void
{
    $flash_messages = flashes();
    $current_user = current_user();
    extract($data);
    ob_start();
    include __DIR__ . "/templates/{$template}.php";
    $content = ob_get_clean();
    include __DIR__ . '/templates/layout.php';
    exit;
}

function redirect(string $path): void
{
    header("Location: {$path}");
    exit;
}

function flash(string $message, string $category = 'info'): void
{
    $_SESSION['flash'][] = [$category, $message];
}

function flashes(): array
{
    $msgs = $_SESSION['flash'] ?? [];
    $_SESSION['flash'] = [];
    return $msgs;
}

function current_user(): ?array
{
    static $cached = false;
    if ($cached !== false) {
        return $cached;
    }
    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) {
        $cached = null;
        return null;
    }
    global $db;
    $stmt = $db->prepare('SELECT * FROM users WHERE id = :id');
    $stmt->execute([':id' => $userId]);
    $cached = $stmt->fetch() ?: null;
    return $cached;
}

function require_login(?string $role = null): array
{
    $user = current_user();
    if (!$user) {
        flash('Please log in first.', 'warning');
        redirect('/login');
    }
    if ($role && $user['role'] !== $role) {
        flash('Not authorized for this area.', 'danger');
        redirect('/');
    }
    return $user;
}

function handle_home(PDO $db): void
{
    $query = trim($_GET['q'] ?? '');
    $results = [];
    $alternatives = [];
    if ($query !== '') {
        $stmt = $db->prepare("
            SELECT pm.id,
                   pm.price,
                   pm.stock_status,
                   pm.quantity,
                   m.id AS medicine_id,
                   m.name AS medicine_name,
                   m.category AS medicine_category,
                   m.description AS medicine_description,
                   p.id AS pharmacy_id,
                   p.name AS pharmacy_name,
                   p.location AS pharmacy_location
            FROM pharmacy_medicines pm
            JOIN medicines m ON pm.medicine_id = m.id
            JOIN pharmacies p ON pm.pharmacy_id = p.id
            WHERE m.name LIKE :q AND p.status = 'approved'
        ");
        $stmt->execute([':q' => "%{$query}%"]);
        $results = $stmt->fetchAll();

        $category = null;
        if ($results) {
            $category = $results[0]['medicine_category'];
        } else {
            $stmt = $db->prepare('SELECT category FROM medicines WHERE name LIKE :q LIMIT 1');
            $stmt->execute([':q' => "%{$query}%"]);
            $row = $stmt->fetch();
            $category = $row['category'] ?? null;
        }
        if ($category) {
            $stmt = $db->prepare('SELECT id, name, category, description FROM medicines WHERE category = :cat AND name NOT LIKE :q LIMIT 4');
            $stmt->execute([':cat' => $category, ':q' => "%{$query}%"]);
            $alternatives = $stmt->fetchAll();
        }
    }
    $pharmacies = $db->query("SELECT id, name, location, phone FROM pharmacies WHERE status = 'approved' LIMIT 4")->fetchAll();
    render('home', compact('query', 'results', 'alternatives', 'pharmacies'));
}

function handle_login(PDO $db): void
{
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $stmt = $db->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        flash('Welcome back!', 'success');
        redirect('/');
    }
    flash('Invalid credentials.', 'danger');
    redirect('/login');
}

function handle_register(PDO $db): void
{
    $name = trim($_POST['name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'patient';

    $stmt = $db->prepare('SELECT 1 FROM users WHERE email = :email');
    $stmt->execute([':email' => $email]);
    if ($stmt->fetchColumn()) {
        flash('Email already registered.', 'warning');
        redirect('/register');
    }

    $db->beginTransaction();
    $stmt = $db->prepare('INSERT INTO users (name, email, password_hash, role) VALUES (:name, :email, :hash, :role)');
    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':hash' => password_hash($password, PASSWORD_DEFAULT),
        ':role' => $role,
    ]);
    $userId = (int)$db->lastInsertId();

    if ($role === 'pharmacist') {
        $pharmacy = [
            'name' => trim($_POST['pharmacy_name'] ?? ''),
            'location' => trim($_POST['location'] ?? ''),
            'license_number' => trim($_POST['license'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'status' => 'pending',
            'owner_id' => $userId,
        ];
        $stmt = $db->prepare('INSERT INTO pharmacies (name, location, license_number, phone, status, owner_id) VALUES (:name, :location, :license, :phone, :status, :owner)');
        $stmt->execute([
            ':name' => $pharmacy['name'],
            ':location' => $pharmacy['location'],
            ':license' => $pharmacy['license_number'],
            ':phone' => $pharmacy['phone'],
            ':status' => $pharmacy['status'],
            ':owner' => $pharmacy['owner_id'],
        ]);
        flash('Pharmacy submitted for approval. You can log in after admin approval.', 'info');
    } else {
        flash('Account created. You can now log in.', 'success');
    }
    $db->commit();
    redirect('/login');
}

function handle_pharmacist_dashboard(PDO $db): void
{
    $user = require_login('pharmacist');
    $stmt = $db->prepare('SELECT * FROM pharmacies WHERE owner_id = :owner LIMIT 1');
    $stmt->execute([':owner' => $user['id']]);
    $pharmacy = $stmt->fetch();
    if (!$pharmacy) {
        flash('No pharmacy profile found.', 'warning');
        redirect('/');
    }
    $medicines = $db->query('SELECT id, name, category FROM medicines ORDER BY name')->fetchAll();
    $stmt = $db->prepare('
        SELECT pm.*, m.name AS medicine_name
        FROM pharmacy_medicines pm
        JOIN medicines m ON pm.medicine_id = m.id
        WHERE pm.pharmacy_id = :pid
        ORDER BY m.name
    ');
    $stmt->execute([':pid' => $pharmacy['id']]);
    $inventory = $stmt->fetchAll();
    render('dashboard_pharmacist', compact('pharmacy', 'medicines', 'inventory'));
}

function handle_inventory_add(PDO $db): void
{
    $user = require_login('pharmacist');
    $stmt = $db->prepare('SELECT id FROM pharmacies WHERE owner_id = :owner LIMIT 1');
    $stmt->execute([':owner' => $user['id']]);
    $pharmacy = $stmt->fetch();
    if (!$pharmacy) {
        flash('No pharmacy profile found.', 'warning');
        redirect('/');
    }
    $medId = (int)($_POST['medicine_id'] ?? 0);
    $price = (float)($_POST['price'] ?? 0);
    $qty = (int)($_POST['quantity'] ?? 0);
    $status = $_POST['stock_status'] ?? 'in_stock';

    $stmt = $db->prepare('SELECT id FROM pharmacy_medicines WHERE pharmacy_id = :pid AND medicine_id = :mid');
    $stmt->execute([':pid' => $pharmacy['id'], ':mid' => $medId]);
    $existing = $stmt->fetchColumn();
    if ($existing) {
        $stmt = $db->prepare('UPDATE pharmacy_medicines SET price = :price, quantity = :qty, stock_status = :status WHERE id = :id');
        $stmt->execute([':price' => $price, ':qty' => $qty, ':status' => $status, ':id' => $existing]);
    } else {
        $stmt = $db->prepare('INSERT INTO pharmacy_medicines (pharmacy_id, medicine_id, price, stock_status, quantity) VALUES (:pid, :mid, :price, :status, :qty)');
        $stmt->execute([':pid' => $pharmacy['id'], ':mid' => $medId, ':price' => $price, ':status' => $status, ':qty' => $qty]);
    }
    flash('Inventory updated.', 'success');
    redirect('/pharmacist');
}

function handle_reserve(PDO $db, int $itemId): void
{
    $user = current_user();
    if (!$user) {
        flash('Please log in as a patient to place a request.', 'warning');
        redirect('/login');
    }
    if ($user['role'] !== 'patient') {
        flash('Only patients can place reservations.', 'danger');
        redirect('/');
    }

    $stmt = $db->prepare('SELECT * FROM pharmacy_medicines WHERE id = :id');
    $stmt->execute([':id' => $itemId]);
    $item = $stmt->fetch();
    if (!$item) {
        flash('Item not found.', 'danger');
        redirect('/');
    }
    $note = trim($_POST['note'] ?? '') ?: null;
    $stmt = $db->prepare('INSERT INTO reservations (user_id, pharmacy_id, medicine_id, status, note, created_at) VALUES (:user, :pid, :mid, :status, :note, :created)');
    $stmt->execute([
        ':user' => $user['id'],
        ':pid' => $item['pharmacy_id'],
        ':mid' => $item['medicine_id'],
        ':status' => 'pending',
        ':note' => $note,
        ':created' => date('Y-m-d H:i:s'),
    ]);
    $medName = $db->query('SELECT name FROM medicines WHERE id = ' . (int)$item['medicine_id'])->fetchColumn() ?: '';
    flash('Reservation sent to the pharmacy.', 'success');
    redirect('/?q=' . urlencode($medName));
}

function handle_patient_requests(PDO $db): void
{
    $user = require_login('patient');
    $stmt = $db->prepare('
        SELECT r.*, m.name AS medicine_name, p.name AS pharmacy_name, p.location AS pharmacy_location
        FROM reservations r
        JOIN pharmacies p ON r.pharmacy_id = p.id
        JOIN medicines m ON r.medicine_id = m.id
        WHERE r.user_id = :uid
        ORDER BY r.created_at DESC
    ');
    $stmt->execute([':uid' => $user['id']]);
    $reservations = $stmt->fetchAll();
    render('patient_requests', compact('reservations'));
}

function handle_pharmacist_requests(PDO $db): void
{
    $user = require_login('pharmacist');
    $stmt = $db->prepare('SELECT * FROM pharmacies WHERE owner_id = :owner LIMIT 1');
    $stmt->execute([':owner' => $user['id']]);
    $pharmacy = $stmt->fetch();
    if (!$pharmacy) {
        flash('No pharmacy profile found.', 'warning');
        redirect('/');
    }
    $stmt = $db->prepare('
        SELECT r.*, u.name AS user_name, u.email AS user_email, m.name AS medicine_name
        FROM reservations r
        JOIN users u ON r.user_id = u.id
        JOIN medicines m ON r.medicine_id = m.id
        WHERE r.pharmacy_id = :pid
        ORDER BY r.created_at DESC
    ');
    $stmt->execute([':pid' => $pharmacy['id']]);
    $reservations = $stmt->fetchAll();
    render('pharmacist_requests', compact('reservations', 'pharmacy'));
}

function handle_reservation_update(PDO $db, int $reservationId, string $action): void
{
    $user = require_login('pharmacist');
    $stmt = $db->prepare('SELECT * FROM pharmacies WHERE owner_id = :owner LIMIT 1');
    $stmt->execute([':owner' => $user['id']]);
    $pharmacy = $stmt->fetch();
    if (!$pharmacy) {
        flash('No pharmacy profile found.', 'warning');
        redirect('/');
    }
    $stmt = $db->prepare('SELECT * FROM reservations WHERE id = :id');
    $stmt->execute([':id' => $reservationId]);
    $reservation = $stmt->fetch();
    if (!$reservation || (int)$reservation['pharmacy_id'] !== (int)$pharmacy['id']) {
        flash('Not authorized.', 'danger');
        redirect('/');
    }
    $status = $action === 'confirm' ? 'confirmed' : 'declined';
    $stmt = $db->prepare('UPDATE reservations SET status = :status WHERE id = :id');
    $stmt->execute([':status' => $status, ':id' => $reservationId]);
    flash('Reservation updated.', 'success');
    redirect('/pharmacist/requests');
}

function handle_admin_dashboard(PDO $db): void
{
    require_login('admin');
    $pending = $db->query("SELECT * FROM pharmacies WHERE status = 'pending'")->fetchAll();
    $stats = [
        'users' => (int)$db->query('SELECT COUNT(*) FROM users')->fetchColumn(),
        'pharmacies' => (int)$db->query('SELECT COUNT(*) FROM pharmacies')->fetchColumn(),
        'medicines' => (int)$db->query('SELECT COUNT(*) FROM medicines')->fetchColumn(),
        'reservations' => (int)$db->query('SELECT COUNT(*) FROM reservations')->fetchColumn(),
    ];
    render('admin_dashboard', compact('pending', 'stats'));
}

function handle_moderate_pharmacy(PDO $db, int $pharmacyId, string $action): void
{
    require_login('admin');
    $stmt = $db->prepare('SELECT * FROM pharmacies WHERE id = :id');
    $stmt->execute([':id' => $pharmacyId]);
    $pharmacy = $stmt->fetch();
    if (!$pharmacy) {
        flash('Pharmacy not found.', 'danger');
        redirect('/admin');
    }
    $newStatus = $action === 'approve' ? 'approved' : 'rejected';
    $stmt = $db->prepare('UPDATE pharmacies SET status = :status WHERE id = :id');
    $stmt->execute([':status' => $newStatus, ':id' => $pharmacyId]);
    flash('Pharmacy ' . ($newStatus === 'approved' ? 'approved' : 'rejected') . '.', $newStatus === 'approved' ? 'success' : 'warning');
    redirect('/admin');
}

function init_db(PDO $db): void
{
    $db->exec('
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT NOT NULL UNIQUE,
            password_hash TEXT NOT NULL,
            role TEXT NOT NULL
        );
    ');
    $db->exec('
        CREATE TABLE IF NOT EXISTS pharmacies (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            location TEXT NOT NULL,
            license_number TEXT NOT NULL,
            phone TEXT NOT NULL,
            status TEXT NOT NULL,
            owner_id INTEGER,
            FOREIGN KEY(owner_id) REFERENCES users(id)
        );
    ');
    $db->exec('
        CREATE TABLE IF NOT EXISTS medicines (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            description TEXT,
            category TEXT
        );
    ');
    $db->exec('
        CREATE TABLE IF NOT EXISTS pharmacy_medicines (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            pharmacy_id INTEGER NOT NULL,
            medicine_id INTEGER NOT NULL,
            price REAL,
            stock_status TEXT,
            quantity INTEGER DEFAULT 0,
            FOREIGN KEY(pharmacy_id) REFERENCES pharmacies(id),
            FOREIGN KEY(medicine_id) REFERENCES medicines(id)
        );
    ');
    $db->exec('
        CREATE TABLE IF NOT EXISTS notifications (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER,
            medicine_id INTEGER,
            type TEXT,
            message TEXT,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP,
            read INTEGER DEFAULT 0,
            FOREIGN KEY(user_id) REFERENCES users(id),
            FOREIGN KEY(medicine_id) REFERENCES medicines(id)
        );
    ');
    $db->exec('
        CREATE TABLE IF NOT EXISTS reservations (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            pharmacy_id INTEGER NOT NULL,
            medicine_id INTEGER NOT NULL,
            status TEXT NOT NULL,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP,
            note TEXT,
            FOREIGN KEY(user_id) REFERENCES users(id),
            FOREIGN KEY(pharmacy_id) REFERENCES pharmacies(id),
            FOREIGN KEY(medicine_id) REFERENCES medicines(id)
        );
    ');
}

function seed_if_empty(PDO $db): void
{
    $count = (int)$db->query('SELECT COUNT(*) FROM users')->fetchColumn();
    if ($count > 0) {
        return;
    }
    $db->beginTransaction();
    $db->prepare('INSERT INTO users (name, email, role, password_hash) VALUES (?,?,?,?)')->execute([
        'Admin',
        'admin@maf.local',
        'admin',
        password_hash('admin123', PASSWORD_DEFAULT),
    ]);
    $db->prepare('INSERT INTO users (name, email, role, password_hash) VALUES (?,?,?,?)')->execute([
        'Alice Patient',
        'alice@example.com',
        'patient',
        password_hash('alice123', PASSWORD_DEFAULT),
    ]);
    $db->prepare('INSERT INTO users (name, email, role, password_hash) VALUES (?,?,?,?)')->execute([
        'City Pharmacy Owner',
        'owner@citypharmacy.com',
        'pharmacist',
        password_hash('owner123', PASSWORD_DEFAULT),
    ]);
    $pharmacistId = (int)$db->lastInsertId();

    $db->prepare('INSERT INTO pharmacies (name, location, license_number, phone, status, owner_id) VALUES (?,?,?,?,?,?)')->execute([
        'City Pharmacy',
        'Kampala CBD',
        'LIC-001',
        '+256 700 000001',
        'approved',
        $pharmacistId,
    ]);
    $pharmacyId = (int)$db->lastInsertId();

    $meds = [
        ['Panadol', 'Pain relief tablets', 'Analgesic'],
        ['Paracetamol', 'Pain and fever relief', 'Analgesic'],
        ['Calpol', 'Pediatric pain relief syrup', 'Analgesic'],
        ['Augmentin', 'Antibiotic', 'Antibiotic'],
        ['Insulin', 'Diabetes control', 'Emergency'],
    ];
    foreach ($meds as $med) {
        $db->prepare('INSERT INTO medicines (name, description, category) VALUES (?,?,?)')->execute($med);
    }
    $medicineIds = $db->query('SELECT id, name FROM medicines')->fetchAll(PDO::FETCH_KEY_PAIR);
    $db->prepare('INSERT INTO pharmacy_medicines (pharmacy_id, medicine_id, price, stock_status, quantity) VALUES (?,?,?,?,?)')->execute([$pharmacyId, $medicineIds['Panadol'], 5000, 'in_stock', 24]);
    $db->prepare('INSERT INTO pharmacy_medicines (pharmacy_id, medicine_id, price, stock_status, quantity) VALUES (?,?,?,?,?)')->execute([$pharmacyId, $medicineIds['Paracetamol'], 4500, 'in_stock', 18]);
    $db->prepare('INSERT INTO pharmacy_medicines (pharmacy_id, medicine_id, price, stock_status, quantity) VALUES (?,?,?,?,?)')->execute([$pharmacyId, $medicineIds['Augmentin'], 18000, 'out_of_stock', 0]);

    $db->prepare('INSERT INTO reservations (user_id, pharmacy_id, medicine_id, status, note, created_at) VALUES (?,?,?,?,?,?)')->execute([
        2, // Alice
        $pharmacyId,
        $medicineIds['Panadol'],
        'pending',
        'Sample request for demo',
        date('Y-m-d H:i:s'),
    ]);
    $db->commit();
}
