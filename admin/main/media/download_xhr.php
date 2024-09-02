<?php
//get list from redis
$list=$this->get('urlsToDownload');
foreach($list as $table => $urls){
    if($urls!=false) {
        foreach ($urls as $id => $url) {
            download_url_and_update($url, $table, $id);
    }
    }
}
// Include the above logic here to query, download, and update the images
        // Adding from a URL
    function download_url_and_update($url,$table,$id){
    $filename = basename($url);
        // Download the image from the URL and save it to the media/slideshow directory
        $targetFilePath = '/var/www/vivalibro/media/slideshow/' . $filename;
        if (file_put_contents($targetFilePath, file_get_contents($url))) {
            $q = $this->db->q("UPDATE $table SET img=? WHERE id=?",[$filename,$id]);
            return !$q
                ? json_encode(['status' => 'fail'])
                : json_encode([
                    'status' => 'success',
                    'filename'=>$filename
                ]);
        } else {
            return json_encode(['status' => 'error', 'message' => 'Failed to download image']);
        }
    }

// Then return a success message
echo json_encode(['message' => 'Images downloaded and database updated successfully!']);
?>
