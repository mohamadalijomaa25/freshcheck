<?php
// backend/config/db.php

function db() {
  $host = getenv('DB_HOST') ?: '127.0.0.1';
  $name = getenv('DB_NAME') ?: 'freshcheck_db';
  $user = getenv('DB_USER') ?: 'root';
  $pass = getenv('DB_PASS') ?: '';
  $port = getenv('DB_PORT') ?: '3306';

  $dsn = "mysql:host=$host;port=$port;dbname=$name;charset=utf8mb4";

  $pdo = new PDO($dsn, $user, $pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]);

  return $pdo;
}
