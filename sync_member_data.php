<?php
require_once 'app/config/config.php';

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get all users
    $stmt = $pdo->query("SELECT * FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_OBJ);

    foreach ($users as $user) {
        if ($user->role_id == 1 || $user->role_id == 2) {
            continue; // Skip admin and librarian
        }

        // Check if member exists
        $stmtMember = $pdo->prepare("SELECT * FROM members WHERE user_id = :user_id");
        $stmtMember->execute([':user_id' => $user->id]);
        $member = $stmtMember->fetch(PDO::FETCH_OBJ);

        $parts = explode(' ', trim($user->name), 2);
        $firstName = $parts[0];
        $lastName = isset($parts[1]) ? $parts[1] : '';

        if (!$member) {
            // Create member
            $suffixes = [24, 25, 26];
            $suffix = $suffixes[array_rand($suffixes)];
            $memberIdString = 'MEM-' . rand(1000, 9999) . '-' . $suffix;

            $insert = $pdo->prepare("INSERT INTO members (user_id, member_id_string, first_name, last_name, email, joined_date, status) VALUES (:user_id, :member_id_string, :first_name, :last_name, :email, :joined_date, 'Active')");
            $insert->execute([
                ':user_id' => $user->id,
                ':member_id_string' => $memberIdString,
                ':first_name' => $firstName,
                ':last_name' => $lastName,
                ':email' => $user->email,
                ':joined_date' => date('Y-m-d H:i:s')
            ]);
            echo "Created member profile for User ID {$user->id} ({$user->name})\n";
        } else {
            // Sync details
            $update = $pdo->prepare("UPDATE members SET first_name = :first_name, last_name = :last_name, email = :email WHERE user_id = :user_id");
            $update->execute([
                ':first_name' => $firstName,
                ':last_name' => $lastName,
                ':email' => $user->email,
                ':user_id' => $user->id
            ]);
            echo "Synced member details for User ID {$user->id} ({$user->name})\n";
        }
    }

    echo "Finished syncing member data.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
