<h3>WEBSERVER</h3>
<span id="webserver"><?=$setup->is_webserver()['name'];?></span>
<span id="webserverversion"><?=$setup->is_webserver()['version'];?></span>
<h3 id="system"><?=$setup->is_os()?></h3>
<?php
$webserver=$setup->is_webserver();
$mysqlVersion = shell_exec('mysql --version');
echo $mysqlVersion;

echo $fspace= @disk_free_space("/")/(1024*1024*1024);
echo $mem= $setup->mem()/(1024*1024);
exec ("find ".SITE_ROOT." -type d -exec chmod 0777 {} +");
exec ("find ".SITE_ROOT." -type f -exec chmod 0777 {} +");

echo $gaiajson['config'];
?>
<div id="version"></div>