<?php
$baseDirectory = '/var/www/vivalibro/media';

$requestedFolder = isset($_GET['folder']) ? $_GET['folder'] : '';
$directory = realpath($baseDirectory . '/' . $requestedFolder);
$page = isset($_GET['pag']) ? (int)$_GET['pag'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 100;
$offset = ($page - 1) * $limit;


// Ensure the directory exists and is within the base media directory
if (!$directory || strpos($directory, realpath($baseDirectory)) !== 0) {
    echo json_encode(['error' => 'Invalid directory']);
    exit;
}

if (!is_dir($directory)) {
    echo json_encode(['error' => 'Directory does not exist']);
    exit;
} else {
    $allFiles = array_diff(scandir($directory), array('..', '.'));
    if ($allFiles === false) {
        echo json_encode(['error' => 'Failed to read directory']);
    } else {
        $files = array_slice($allFiles, $offset, $limit);
        if ($files === false) {
            echo json_encode(['error' => 'Failed to read directory']);
        } else {
            $response = [
                'files' => [],
                'directories' => [],
                'total' => count($allFiles),
                'page' => $page,
                'limit' => $limit,
                'currentFolder' => $requestedFolder,
                'parentFolder' => $requestedFolder ? dirname($requestedFolder) : '', // Calculate parent folder
            ];

            foreach ($files as $file) {
                if (is_dir($directory . '/' . $file)) {
                    $response['directories'][] = $file;
                } else {
                    $response['files'][] = $file;
                }
            }

            echo json_encode($response);
        }
    }
}
?>
