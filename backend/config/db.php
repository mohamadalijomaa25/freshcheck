<?php
// backend/config/db.php

function db() {
  $host = getenv('DB_HOST') ?: '127.0.0.1';
  $name = getenv('DB_NAME') ?: 'freshcheck_db';
  $user = getenv('DB_USER') ?: 'root';
  $pass = getenv('DB_PASS') ?: '';
  $port = getenv('DB_PORT') ?: '3306';

  // SSL: set DB_SSL=1 on Render for Aiven
  $useSSL = (getenv('DB_SSL') === '1');

  $dsn = "mysql:host=$host;port=$port;dbname=$name;charset=utf8mb4";
  $options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ];

  if ($useSSL) {
    // Aiven requires SSL. Render can use system CA store for MySQL SSL.
    // If Render fails to verify, we can add a CA file later.
    $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
    $options[PDO::MYSQL_ATTR_SSL_CA] = null;
  }

  $pdo = new PDO($dsn, $user, $pass, $options);
  return $pdo;
}
