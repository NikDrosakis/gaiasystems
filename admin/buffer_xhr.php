<?php
/*
try {
    // Initialize buffer for errors and HTML content
    $buffer = [
        'errors' => [],
        'html' => ''
    ];
    if (!ob_start("ob_gzhandler")) ob_start();
    // Retrieve parameters
    $file = $_GET['file'] ?? '';
    $G = $_GET['pms'] ?? '';
    // Include the specified file
    include $file . '.php';
    // Capture HTML content
    $buffer['html'] = ob_get_clean();

} catch (Exception $e) {
    // Handle exception and set error in buffer
    $buffer['errors'][] = $e->getMessage();
}
// Send JSON response containing both errors and HTML content
header('Content-Type: application/json');
echo json_encode($buffer);

    $buffer = "";
    if (!ob_start("ob_gzhandler")) ob_start();
    $file = $_GET['file'];
    $safeFile = preg_replace('/[^a-z0-9_]/i', '', $file);
    $pms = json_decode($_GET['pms'], true);
    $arg = $_GET['arg'];
  //  $argpms = json_decode($_GET['argpms'], true);
    $class = explode('/', $f)[count(explode('/', $f)) - 1];
        //$this->G['page'] = $pms[0];
        //$this->G['param'] = $pms[1];
        include  $safeFile. '.php';
    $buffer = ob_get_clean();
    flush();
    ob_end_clean();
    echo $buffer;

*/
