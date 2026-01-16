<?php
try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=clubs-system;charset=utf8",
        "root",
        ""
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode([
        'error' => 'Database connection failed'
    ]));
}
