<style>
.navbar {
  display: flex;
  justify-content: flex-start;
  align-items: center;
}
.menu a.active {
    background-color: #4CAF50;
    color: white;
}
.menu {
  margin-top:8px;
  list-style: none;
  display: flex;
}
.menu a:hover {
    background-color: #ddd;
    color: black;
}
.menu a {
    float: left;
    display: block;
    color: #f2f2f2;
    text-align: center;
    padding: 10px 16px;
    text-decoration: none;
    font-size: 17px;
}
.menu-item {
    position: relative;
    list-style: none;
}
.menu-item > a {
    display: block;
    padding: 15px 20px;
    text-decoration: none;
    color: #fff;
    transition: background 0.3s;
}
.menu-item > a:hover {
    background: #555;
}
.submenu {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    list-style: none;
    background: #444;
    padding: 0;
    margin: 0;
    z-index: 1000;
}
.submenu_wide {
        display: none;
    position: absolute;
    top: 100%;
    left: -190px;
    list-style: none;
    background: #444;
    padding: 0;
    margin: 0;
    z-index: 1000;
    width: 60vw;
}

.menu-item:hover .submenu {
    display: block;
}
.menu-item {
    position: relative;
    display: flex;
    flex-direction: column;
}
.submenu li a {
    display: block;
    padding: 10px 20px;
    text-decoration: none;
    color: #fff;
    transition: background 0.3s;
}
.submenu li a:hover {
    background: #666;
}
.grand-panel {
    padding: 20px;
    background: #fff;
    color: #333;
    max-width: 1200px;
    margin: 0 auto;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}
</style>

<!----------------------------the MENU---------------------------->
<div id="dashbar">
    <div id="progressBarContainer"><div id="progressBar"><p id="progressText"></p></div></div>
    <div class="logo_image_id">
        <img src="<?=isset($_COOKIE['GSIMG']) ? UPLOADS.$_COOKIE['GSIMG'] : "/img/user.png"?>" width="48" height="48" style="margin-top: 3px;">
    </div>

    <div class="gs-desktop">
        <p><?=!isset($_COOKIE['GSGRP']) ? '' : $this->usergrps[$_COOKIE['GSGRP']]?></p>
        <p><?=isset($_COOKIE['GSNAME']) ?  $_COOKIE['GSNAME'] : ''?></p>
    </div>


	<div id="menuwrapper">
         <ul id="js-tree-menu">
             <li><a style="<?='layout'==$this->page ? 'color:white;' : ''?>" href='/admin'><span class="glyphicon glyphicon-<?=$this->getIcon('home')?>" aria-hidden="true"></span>Home</a></li>
            <?php
          //  $permissions= $this->db->f("SELECT permissions FROM usergrp WHERE id=?",array($_COOKIE['GSGRP']))['permissions'];            
			//$permissions=json_decode($permissions,true);
		//	if(is_json($permissions)){
		//	$permissionList=json_decode($permissions,true);			
            foreach($this->apages as $page =>$vals){
            $icon=$vals['icon'];
		//	if(in_array($mpage,$permissionList)){
            $targ= $page == $this->page ? 'style="color:white;"' : '';
            ?>
            <li <?=$page==$this->page ? $targ : ''?>><a href="/admin/<?=$page?>"><span style="margin:0 4px 0 0" class="glyphicon glyphicon-<?=$icon?>" aria-hidden="true"></span><?=$vals['title']?></a></li>
              <?php 	}
            //}}  ?>
        </ul>
    </div>



</div>
<header>
    <nav class="navbar">

        <ul class="menu">

            <li class="menu-item">
             <a href="/admin/<?=$this->page?>" class="<?=$this->sub=='' ? 'active':''?>">Main</a>
			   <?php
			   if(!empty($this->G['apages'][$this->page]['subs'])){
			   $subs=$this->apages[$this->page]['subs'];
			   ?>
					<ul class="submenu">
						<?php foreach($subs as $subtitle=>$sublink){ 
							$sublinkhref= $this->page=="tools" ? 'href="'.$sublink.'" target="_blank"' : 'href="/admin/'.$this->page.'?sub='.$sublink.'"';
						?>
					<li><a <?=$sublinkhref?> class="<?=$sublink==$this->sub ? 'active':''?>"><?=ucfirst($subtitle)?></a></li>
					<?php } ?>
					</ul>
				<?php } ?>
            </li>

		    <li class="menu-item"><a id="globs_menu"><span class="glyphicon glyphicon-<?=$this->getIcon('global')?>"></span>Preferences</a>
				<ul class="submenu_wide" id="globals_menu">
				<?php include "globs.php";?>
				</ul>
			</li>


        <li class="menu-item">
                     <a>View</a>
        					<ul class="submenu">
        					<li><a class="">Short Sidebar</a></li>
        					<li><a class="">Wide Sidebar</a></li>
        					<li><a class="">Short Navigation</a></li>
        					<li><a class="">Dark</a></li>
        					</ul>
       </li>

        <li class="menu-item"><a href="/"><span class="glyphicon glyphicon-<?=$this->getIcon('home')?>"></span>Goto Public</a></li>
        <li class="menu-item"><a style="cursor:pointer" onclick="s.init.logout()" id="logout"><span style="margin: 0 8px 0px 0;" class="glyphicon glyphicon-hand-right" aria-hidden="true"></span>Logout</a></li>
        </ul>


        <div id="c_active_users"></div>
    </nav>
</header>