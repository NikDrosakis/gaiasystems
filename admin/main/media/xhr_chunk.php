<?php
$targetDir = "/var/www/vivalibro/media/chunks/";
$fileName = isset($_POST['fileName']) ? $_POST['fileName'] : '';
$chunkNumber = isset($_POST['chunkNumber']) ? intval($_POST['chunkNumber']) : 0;
$totalChunks = isset($_POST['totalChunks']) ? intval($_POST['totalChunks']) : 0;


// Ensure the target directory exists
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

$tempFile = $targetDir . $fileName . '.part' . $chunkNumber;

// Save the uploaded chunk to a temporary file
if (move_uploaded_file($_FILES['file']['tmp_name'], $tempFile)) {
    // If all chunks have been uploaded, start assembling the final file
    if ($chunkNumber + 1 == $totalChunks) {
        $finalFile = $targetDir . $fileName;

        // Open the final file for writing
        $fp = fopen($finalFile, 'wb');
        if (!$fp) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to open final file for writing']);
            exit;
        }

        // Assemble all chunks into the final file
        for ($i = 0; $i < $totalChunks; $i++) {
            $partFile = $targetDir . $fileName . '.part' . $i;
            if (file_exists($partFile)) {
                $chunk = file_get_contents($partFile);
                fwrite($fp, $chunk);
                unlink($partFile); // Delete the chunk after adding it to the final file
            } else {
                // Handle the case where a chunk is missing
                fclose($fp);
                unlink($finalFile); // Clean up by deleting the incomplete final file
                echo json_encode(['status' => 'error', 'message' => 'Missing chunk file: ' . $partFile]);
                exit;
            }
        }

        fclose($fp); // Close the final file after writing
        echo json_encode(['status' => 'success', 'message' => 'File uploaded and assembled successfully']);
    } else {
        // If not all chunks are uploaded yet, return a partial status
        echo json_encode(['status' => 'partial', 'message' => 'Chunk ' . ($chunkNumber + 1) . ' uploaded']);
    }
} else {
    // Handle the error if the chunk couldn't be moved to the temp file
    echo json_encode(['status' => 'error', 'message' => 'Failed to upload chunk ' . ($chunkNumber + 1)]);
}
?>
