<?php
require 'C:\XAMPP1\htdocs\LibraryManagementSystem\app\config\config.php';
try {
    $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT id FROM members WHERE user_id IS NOT NULL");
    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $updateStmt = $pdo->prepare("UPDATE members SET member_id_string = :member_id_string WHERE id = :id");

    $suffixes = [24, 25, 26];

    foreach ($members as $member) {
        // Generate new ID like MEM-XXXX-24
        $suffix = $suffixes[array_rand($suffixes)];
        $newIdString = 'MEM-' . rand(1000, 9999) . '-' . $suffix;
        $updateStmt->execute([
            ':member_id_string' => $newIdString,
            ':id' => $member['id']
        ]);
        echo "Updated member ID " . $member['id'] . " to " . $newIdString . "\n";
    }

    echo "Successfully updated dummy IDs.\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
