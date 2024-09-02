<?php
$img=$G['my']['img']=='' ? $bookdefaultimg: '/media/'.$G['my']['img'];
?>
<!-- EDIT / SHOW-->
<a href="/writer">Back to Writers</a>
<span style="float:left;" onclick="g.ui.goto(['previous','writer','id',s.get.id,'/writer?id='])" class="next glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
<span style="float:right" onclick="g.ui.goto(['next','writer','id',s.get.id,'/writer?id='])" class="next glyphicon glyphicon-chevron-right" aria-hidden="true"></span>

<div style="width:98%;background:#fcfbec">
    <h2 id="titlebig"><?=$G['my']['name']?></h2>

    <div style="width:30%;float:left;margin:15px 15px 15px 15px;">
        <img id="bookimg" src="<?=$img?>" style="max-height:350px;">
    </div>

    <div style="display:inline-block; width:56%;margin:2%">
        <label>Name:</label><input class="input" id="name" value="<?=$G['my']['name']?>">
        <label>Firstname:</label><input class="input" id="firstname" value="<?=$G['my']['firstname']?>">
        <label>Lastname:</label><input class="input" id="lastname" value="<?=$G['my']['lastname']?>">
        <label>Email:</label><input class="input" id="mail" value="<?=$G['my']['mail']?>">
        <div>
            <label>Bio: </label><textarea class="input" id="content"><?=$G['my']['content']?></textarea>
            <button class="btn btn-primary" id="update">Save Writer</button>
        </div>


    </div>
</div>