<?php
require_once 'app/config/config.php';

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $firstNames = ['John', 'Kwame', 'Kofi', 'Yaw', 'Kojo', 'Ebenezer', 'Samuel', 'Michael', 'Mary', 'Akosua', 'Ama', 'Yaa', 'Abena', 'Grace', 'Sarah', 'Emmanuel', 'Isaac', 'Daniel', 'Esther', 'Martha'];
    $lastNames = ['Mensah', 'Osei', 'Appiah', 'Owusu', 'Asamoah', 'Boateng', 'Agyemang', 'Boakye', 'Oppong', 'Nketia', 'Adu', 'Sarpong', 'Frimpong', 'Amponsah', 'Danquah', 'Gyimah', 'Arthur'];

    // Get all users that are dummy students
    $stmt = $pdo->query("SELECT id, name FROM users WHERE role_id = 3 AND name LIKE 'Student %'");
    $users = $stmt->fetchAll(PDO::FETCH_OBJ);

    $hashedPassword = password_hash('password', PASSWORD_DEFAULT);

    foreach ($users as $user) {
        $first = $firstNames[array_rand($firstNames)];
        $last = $lastNames[array_rand($lastNames)];
        $fullName = $first . ' ' . $last;
        $email = strtolower($first . '.' . $last . '@example.com');

        // Update user
        $updateUser = $pdo->prepare("UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id");
        $updateUser->execute([
            ':name' => $fullName,
            ':email' => $email,
            ':password' => $hashedPassword,
            ':id' => $user->id
        ]);

        // Update member
        $updateMember = $pdo->prepare("UPDATE members SET first_name = :first, last_name = :last, email = :email WHERE user_id = :id");
        $updateMember->execute([
            ':first' => $first,
            ':last' => $last,
            ':email' => $email,
            ':id' => $user->id
        ]);
        
        echo "Updated User ID {$user->id} to {$fullName}\n";
    }

    echo "Finished updating users.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
