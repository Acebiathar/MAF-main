<?php
declare(strict_types=1);

date_default_timezone_set('Africa/Kampala');
session_start();

// 1. PROJECT CONSTANTS
define('VIEW_PATH', __DIR__ . '/resources/views');
define('PUBLIC_PATH', __DIR__ . '/public');

// 2. DATABASE CONNECTION
$dbPath = __DIR__ . '/maf.db';
$db = new PDO('sqlite:' . $dbPath);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// Using OBJ mode makes $item->name work instead of $item['name']
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

init_db($db);
seed_if_empty($db);

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// 3. CSRF PROTECTION (Simple version for your project)
if ($method === 'POST') {
    // In a real Laravel app, this is handled by middleware
    // For now, we ensure the session is active
    if (!isset($_SESSION['user_id']) && !in_array($path, ['/login', '/register', '/contact'])) {
        // Allow public posts, but protect sensitive ones
    }
}

// 4. ROUTING LOGIC
if ($path === '/' && $method === 'GET') {
    handle_home($db);
} elseif ($path === '/how-it-works') {
    render('how');
} elseif ($path === '/about') {
    render('about');
} elseif ($path === '/contact') {
    if ($method === 'POST') {
        flash('Thanks! We received your message.', 'success');
        redirect('/contact');
    }
    render('contact');
} elseif ($path === '/login') {
    if ($method === 'POST') handle_login($db);
    render('auth/login');
} elseif ($path === '/register') {
    if ($method === 'POST') handle_register($db);
    render('auth/register');
} elseif ($path === '/pharmacist' && $method === 'GET') {
    handle_pharmacist_dashboard($db);
} elseif ($path === '/pharmacist/add' && $method === 'POST') {
    handle_inventory_add($db);
} elseif ($path === '/pharmacist/requests' && $method === 'GET') {
    handle_pharmacist_requests($db);
} elseif ($path === '/admin' && $method === 'GET') {
    handle_admin_dashboard($db);
} else {
    // Check for dynamic routes like /reserve/1
    if (preg_match('#^/reserve/(\d+)$#', $path, $m)) {
        handle_reserve($db, (int)$m[1]);
    } else {
        http_response_code(404);
        render('errors/404'); // You can create this later
    }
}

// 5. THE CORE RENDER ENGINE
function render(string $template, array $data = []): void
{
    $alerts = flashes();
    $currentUser = current_user();
    
    // This makes variables like $query available in the template
    extract($data);

    // We use a simple output buffer to mimic Blade's @yield
    ob_start();
    $templatePath = VIEW_PATH . "/{$template}.blade.php";
    
    if (file_exists($templatePath)) {
        include $templatePath;
    } else {
        echo "Template not found: {$template}";
    }
    
    $content = ob_get_clean();

    // Include the main layout which contains the @yield('content')
    // In our manual setup, we just use the $content variable
    include VIEW_PATH . '/layouts/app.blade.php';
    exit;
}

// 6. HELPER FUNCTIONS
function current_user(): ?object
{
    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) return null;
    
    global $db;
    $stmt = $db->prepare('SELECT * FROM users WHERE id = :id');
    $stmt->execute([':id' => $userId]);
    return $stmt->fetch() ?: null;
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

// ... rest of your handle_ functions stay largely the same but use $item->prop ...