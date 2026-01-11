<?php
header('Content-Type: application/json');
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['status' => 'error', 'message' => 'Neautorizovaný přístup!']);
    exit;
}
require_once '../WebSenderAPI.php'; 
function getEnvVal($key) {
    $path = __DIR__ . '/../.env';
    if (!file_exists($path)) return null;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        if (trim($name) == $key) return trim($value);
    }
    return null;
}
$host = getEnvVal('WEBSENDER_HOST');
$port = getEnvVal('WEBSENDER_PORT');
$pass = getEnvVal('WEBSENDER_PASSWORD');
try {
    $ws = new WebSenderAPI($host, $port, $pass);
    if ($ws->connect()) {
        $data = json_decode(file_get_contents('php://input'), true);
        $command = $data['command'] ?? '';
        if ($command) {
            $ws->sendCommand($command);
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Prázdný příkaz']);
        }
        $ws->disconnect();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Chyba připojení (Config?).']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Chyba: ' . $e->getMessage()]);
}
?>