    <h1>Media URL Management</h1>
<p>
start checking DB for https:// to get a list
to download files from internet
and update db
create a list
append list to a table
</p>
<?php
$tableswithimg=[];
// Array to hold URLs to be downloaded
$urlsToDownload = [];
$total=0;
//get tables with img
$tables=$this->get('list_tables');
if(!$tables) {
    $tables = $this->db->list_tables();
    $this->set('list_tables',$tables);
}
$tableswithimg=$this->get('tableswithimg');
if(!$tableswithimg) {
    foreach ($tables as $table) {
        $columns = $this->db->columns($table);
        foreach ($columns as $col) {
            if ($col === "img") {
                $tableswithimg[] = $table;
            }
        }
        $this->set('tableswithimg', $tableswithimg);
    }
}
//xecho($tableswithimg);

//check tables
$urlsToDownload=$this->get('urlsToDownload');
if(!$urlsToDownload) {
    foreach ($tableswithimg as $table) {
        $urlsToDownload[$table] = $this->db->fl(['id', 'img'], $table, "WHERE img LIKE 'http%'");
        $total += count($urlsToDownload[$table]);
        if($total > 1000) break;
    }
    $this->set('urlsToDownload', $urlsToDownload);
}else{
        foreach ($urlsToDownload as $table =>$urlchunk) {
            $total += count($urlchunk);
        }
}

?>
    <h2>Totally <?=$total?> images are on urls</h2>
    <?php if($total > 100){ ?>
        <button id="downloadImages">Download and Update Images</button>
    <?php } ?>

<script>
    document.getElementById('downloadImages').addEventListener('click', function() {
        $.post('/admin/main/media/download_xhr.php',function(response) {
            console.log(response.message);
        });
    });
</script>