<?php
header('Content-Type: application/json');

try {
    $host = getenv('DB_HOST') ?: 'localhost';
    $port = getenv('DB_PORT') ?: '3306';
    $name = getenv('DB_NAME') ?: 'dss_prod01';
    $user = getenv('DB_USER') ?: 'dss_prod01';
    $pass = getenv('DB_PASS') ?: '83wf#I3u8';

    $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";

    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // Test connection
    $stmt = $pdo->query('SELECT 1 AS test');
    $result = $stmt->fetch();

    // Get database info
    $stmt = $pdo->query('SELECT DATABASE() AS db_name, VERSION() AS version');
    $dbInfo = $stmt->fetch();

    // Get table list
    $stmt = $pdo->query('SHOW TABLES');
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode([
        'status' => 'success',
        'connection' => 'OK',
        'test_query' => $result['test'],
        'database' => $dbInfo['db_name'],
        'mysql_version' => $dbInfo['version'],
        'tables' => $tables,
        'table_count' => count($tables)
    ], JSON_PRETTY_PRINT);

} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ], JSON_PRETTY_PRINT);
}
?>
