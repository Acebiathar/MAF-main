function handle_pharmacist_requests(PDO $db): void
{
    $user = require_login('pharmacist');
    
    // Get the pharmacy owned by this user
    $stmt = $db->prepare('SELECT * FROM pharmacies WHERE owner_id = :owner LIMIT 1');
    $stmt->execute([':owner' => $user->id]);
    $pharmacy = $stmt->fetch();

    if (!$pharmacy) {
        flash('No pharmacy profile found.', 'warning');
        redirect('/');
    }

    // Get all reservations for this pharmacy
    $stmt = $db->prepare('
        SELECT r.*, u.name AS user_name, u.email AS user_email, m.name AS medicine_name
        FROM reservations r
        JOIN users u ON r.user_id = u.id
        JOIN medicines m ON r.medicine_id = m.id
        WHERE r.pharmacy_id = :pid
        ORDER BY r.created_at DESC
    ');
    $stmt->execute([':pid' => $pharmacy->id]);
    $reservations = $stmt->fetchAll();

    render('pharmacist_requests', compact('reservations', 'pharmacy'));
}