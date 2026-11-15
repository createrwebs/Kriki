<?php
$pdo = new PDO("mysql:host=mysql-server;dbname=whmcs_db", "whmcs_user", "WhmcsPass2026!");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec("SET sql_mode=''");

$hash = password_hash("AdminPass2026!", PASSWORD_BCRYPT);
$now = date("Y-m-d H:i:s");

// 1. Insert into tblusers
$pdo->exec("DELETE FROM tblusers WHERE email='admin@createai.local'");
$stmt = $pdo->prepare("INSERT INTO tblusers (first_name, last_name, email, password, created_at, updated_at) VALUES ('Admin', 'Master', 'admin@createai.local', ?, ?, ?)");
$stmt->execute([$hash, $now, $now]);
$userId = $pdo->lastInsertId();

// 2. Insert into tbladmins
$pdo->exec("DELETE FROM tbladmins WHERE username='admin'");
$stmt = $pdo->prepare("INSERT INTO tbladmins (roleid, username, password, firstname, lastname, email, disabled, template, authmodule) VALUES (1, 'admin', ?, 'Admin', 'Master', 'admin@createai.local', 0, 'blend', '')");
$stmt->execute([$hash]);
$adminId = $pdo->lastInsertId();

// 3. Link user to admin if tbladmin_user exists
$tables = $pdo->query("SHOW TABLES LIKE 'tbladmin_user'")->fetchAll();
if (!empty($tables)) {
    $pdo->exec("INSERT IGNORE INTO tbladmin_user (admin_id, user_id) VALUES ($adminId, $userId)");
}

echo "Admin user created with ID $adminId and User ID $userId\n";
