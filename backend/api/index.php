<?php
// backend/api/index.php

// CORS (MUST be first output)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/db.php';

function send($code, $data) {
  http_response_code($code);
  echo json_encode($data);
  exit;
}

function readJson() {
  $raw = file_get_contents('php://input');
  $data = json_decode($raw, true);
  return is_array($data) ? $data : [];
}

// ----- Routing -----
// /items
// /items/{id}

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Find "index.php" position and take what comes after it
$pos = stripos($uri, 'index.php');
$path = $pos !== false ? substr($uri, $pos + strlen('index.php')) : '';
$path = trim($path, '/'); // e.g. "items/5"

$parts = $path === '' ? [] : explode('/', $path);

$resource = $parts[0] ?? '';
$id = isset($parts[1]) ? intval($parts[1]) : null;

// For now (no auth yet), fixed user_id = 1
$userId = 1;

try {
  $pdo = db();

  if ($resource === '') {
    send(200, [
      "status" => "ok",
      "message" => "FreshCheck API is running",
      "routes" => ["GET /items", "POST /items", "PUT /items/{id}", "DELETE /items/{id}"]
    ]);
  }

  if ($resource !== 'items') {
    send(404, ["error" => "Not found"]);
  }

  // ---- GET /items ----
  if ($method === 'GET' && $id === null) {
    $stmt = $pdo->prepare("SELECT id, user_id, name, opened_at, safe_days, note, photo_url, created_at
                           FROM items
                           WHERE user_id = ?
                           ORDER BY created_at DESC");
    $stmt->execute([$userId]);
    $items = $stmt->fetchAll();
    send(200, $items);
  }

  // ---- POST /items ----
  if ($method === 'POST' && $id === null) {
    $body = readJson();
    $name = trim($body['name'] ?? '');
    $opened_at = $body['opened_at'] ?? '';
    $safe_days = intval($body['safe_days'] ?? 0);
    $note = $body['note'] ?? null;
    $photo_url = $body['photo_url'] ?? null;

    if ($name === '') send(400, ["error" => "name is required"]);
    if ($safe_days <= 0) send(400, ["error" => "safe_days must be > 0"]);
    if ($opened_at === '') send(400, ["error" => "opened_at is required (YYYY-MM-DD or YYYY-MM-DD HH:MM:SS)"]);

    $stmt = $pdo->prepare("INSERT INTO items (user_id, name, opened_at, safe_days, note, photo_url)
                           VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$userId, $name, $opened_at, $safe_days, $note, $photo_url]);

    $newId = $pdo->lastInsertId();
    send(201, ["id" => intval($newId)]);
  }

  // ---- PUT /items/{id} ----
  if ($method === 'PUT' && $id !== null) {
    $body = readJson();
    $name = trim($body['name'] ?? '');
    $opened_at = $body['opened_at'] ?? '';
    $safe_days = intval($body['safe_days'] ?? 0);
    $note = $body['note'] ?? null;
    $photo_url = $body['photo_url'] ?? null;

    if ($name === '') send(400, ["error" => "name is required"]);
    if ($safe_days <= 0) send(400, ["error" => "safe_days must be > 0"]);
    if ($opened_at === '') send(400, ["error" => "opened_at is required"]);

    $stmt = $pdo->prepare("UPDATE items
                           SET name=?, opened_at=?, safe_days=?, note=?, photo_url=?
                           WHERE id=? AND user_id=?");
    $stmt->execute([$name, $opened_at, $safe_days, $note, $photo_url, $id, $userId]);

    if ($stmt->rowCount() === 0) send(404, ["error" => "Item not found"]);
    send(200, ["message" => "updated"]);
  }

  // ---- DELETE /items/{id} ----
  if ($method === 'DELETE' && $id !== null) {
    $stmt = $pdo->prepare("DELETE FROM items WHERE id=? AND user_id=?");
    $stmt->execute([$id, $userId]);

    if ($stmt->rowCount() === 0) send(404, ["error" => "Item not found"]);
    send(200, ["message" => "deleted"]);
  }

  send(405, ["error" => "Method not allowed"]);
} catch (Exception $e) {
  send(500, ["error" => "Server error", "details" => $e->getMessage()]);
}
