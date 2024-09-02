<?php
//$list=$this->db->list_tables();
//foreach($list as $db=> $table){
	//xecho($table);	//$t[$table]=array_combine($this->db->columns($table),$this->db->comments($table));
//}
//file_put_contents("/var/www/admin/schema.json",json_encode($t,JSON_PRETTY_PRINT));
//xecho($t);   
define('MAIN_SETUP_EXIST',file_exists(G.SITE_ROOT.'gaia.json'));
$gaiajson= jsonget(G.SITE_ROOT.'gaia.json');
$tree= $gaiajson['domains'];
$setup=new Setup();
?>
<div id="mainpage" style="display:block">
<input type="hidden" id="current_version" value="<?=file_get_contents(GAIAROOT."version")?>">
<!----------------------------------------------------------------
							INFO
------------------------------------------------------------------>
<?php
if($this->sub=="info"){
    include "info.php";
//<!----------------------------------------------------------------
							//DOMAIN LIST
//------------------------------------------------------------------>
 }elseif($this->sub==""){
    include "domains.php";
//<!----------------------------------------------------------------
//							TEMPLATES
//------------------------------------------------------------------>
 }elseif($this->sub=="templates"){
include "new.php";
//<!----------------------------------------------------------------
//							NEW DOMAIN
//------------------------------------------------------------------>
}elseif($this->sub=="new"){
include "new.php";
}
?>
</div>

<script src="/admin/main/setup/setup.js"></script>