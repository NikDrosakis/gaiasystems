<?php
$a=$_POST['a'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update sort order
    if ($a=='order') {
        foreach ($_POST['order'] as $slide) {
            $q = $this->db->q("UPDATE wid_slideshow SET sort = ? WHERE id = ?",[$slide['sort'],$slide['id']]);
        }
        echo json_encode(['status' => 'success']);
// Update caption
    }elseif ($a=='caption') {
        $q = $this->db->q("UPDATE wid_slideshow SET caption = ? WHERE id = ?",[$_POST['caption'],$_POST['id']]);
        echo json_encode(['status' => 'success']);
        // Handle slide deletion
    }elseif ($a=='delete') {
        if (isset($_POST['delete'])) {
            $q =$this->db->q("DELETE FROM wid_slideshow WHERE id = ?",[$_POST['delete']]);
            echo json_encode(['status' => 'success']);
        }
    }elseif ($a=='upload_media') {
        // Adding from media folder
        $filename = $_POST['filename'];
        $caption = $_POST['caption'] ?? '';
        $q = $this->db->inse("wid_slideshow",["filename"=> $filename, "caption"=> $caption, "sort"=>9999]);
        echo !$q
            ? json_encode(['status' => 'fail'])
            : json_encode([
                'status' => 'success',
                'filename'=>$filename,
                'caption'=>$caption,
                'sort'=>9999,
                'id'=>$q
            ]);
    }elseif ($a=='upload_file') {
        if (isset($_FILES['upload_image']) && $_FILES['upload_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = $G['MEDIA_ROOT'];
            $uploadFile = $uploadDir . basename($_FILES['upload_image']['name']);
            $filename = $_FILES['upload_image']['name'];
            if (move_uploaded_file($_FILES['upload_image']['tmp_name'], $uploadFile)) {
                // File successfully uploaded, now insert the record into the database
                echo json_encode([
                        'status' => 'success',
                        'filename'=>$filename
                    ]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to upload file']);
            }
        }
    }elseif ($a=='upload_url') {
        // Adding from a URL
        $url = $_POST['url'];
        $filename = basename($url);
        // Download the image from the URL and save it to the media/slideshow directory
        $targetFilePath = '/var/www/vivalibro/media/slideshow/' . $filename;
        if (file_put_contents($targetFilePath, file_get_contents($url))) {
            $q = $this->db->inse("wid_slideshow",["filename"=> $filename]);
            echo !$q
                ? json_encode(['status' => 'fail'])
                : json_encode([
                    'status' => 'success',
                    'filename'=>$filename
                ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to download image']);
        }
    }
}