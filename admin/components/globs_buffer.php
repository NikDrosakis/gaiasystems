<button class="" id="newGlobalBtn">+</button>
<div id="newglobal" style="font-size:12px;padding: 10px;width:200px"></div>

<?php  $globlist=$this->db->fl(array("tag","id"),"globs");  ?>

<div class="gs-databox">

<?php foreach($globlist as $tag => $tagcount){ ?>
<button id="globtitle<?=$tag?>" class="gs-title<?=isset($_COOKIE['globs_tab']) && $_COOKIE['globs_tab']==$tag ? "Active":""?>"><?=$tag?></button>
<?php } ?>

</div>

<?php foreach($globlist as $tag => $tagcount){
$sel= $this->db->fa("SELECT * FROM globs WHERE tag=?",array($tag));
?>

<div id="globs_<?=$tag?>"  class="gs-databox-inside" style="display:<?=isset($_COOKIE['globs_tab']) && $_COOKIE['globs_tab']==$tag ?'block':'none'?>">

<?php for($i=0;$i<count($sel);$i++){
$id=$sel[$i]['id'];
$type=trim($sel[$i]['type']);
?>
<div class="globs-setBox" id="setBox<?=$id?>"  class="img-thumbnail">
	<button style="float: right;" class="btn btn-xs btn-danger" id="delpvar<?=$id?>">X</button>
	<!--TYPE SELECTION-->
		<select class="form-small" id="pvartype<?=$id?>">
		<option value=0>Select</option>
	<?php foreach($this->G['globs_types'] as $typeval){ ?>      <!-------------category loop --------------->
		<option value="<?=$typeval?>" <?=$typeval==$type ? "selected='selected'":''?>><?=$typeval?></option>
		<?php } ?>
	</select>
	        <label class="profile_title"><?=$sel[$i]['name']?></label>

			<?php if($type=='textarea' || $type=='html'){ ?>
				<textarea class="form-control input-sm" class="lang12" id="set<?=$id?>"><?=urldecode($sel[$i]["en"])?></textarea>

			<?php }elseif($type=='json'){ ?>
 <div id="jsoneditor-container-<?= $sel[$i]['id'] ?>" class="jsoneditor-container"></div>
 <textarea id="jsoneditor-<?=$sel[$i]['id'] ?>" style="display:none;" class="jsoneditor-textarea"> <?=json_encode($sel[$i]["en"], JSON_PRETTY_PRINT)?></textarea>
 <button id="save-json<?=$sel[$i]['id'] ?>" class="jsonvalidator btn btn-primary">Validate JSON</button>

			<?php }elseif($type=='code'){ ?>
			<textarea style="height:150px" id="codeditor<?=$id?>" class="codeditor form-control input-sm" class="lang12"><?=json_decode($sel[$i]["en"],true)?></textarea>

			<?php }elseif($type=='boolean'){ ?>
				<label class="switch">
				<input id="set<?=$id?>" onclick="this.value=this.checked ? 1:0" <?=$sel[$i]["en"]=="1" ? 'checked':''?> type="checkbox">
				<span class="slider"></span></label>

			<?php }elseif($type=='integer' || $type=='decimal2'){ ?>
			<input class="form-control input-sm" type="number" class="lang12" id="set<?=$id?>" value="<?=$sel[$i]["en"]?>">

			<?php }elseif($type=='read'){ ?>
			<label><?=$sel[$i]["en"]?></label>

			<?php }elseif($type=='color'){ ?>
			<input class="form-control input-sm" type="color" class="lang12" id="set<?=$id?>" value="<?=urldecode($sel[$i]["en"])?>">
			<!--UPLOAD-->

<?php }else{ ?>
			<input class="form-control input-sm" class="lang12" id="set<?=$id?>" value="<?=urldecode($sel[$i]["en"])?>">
	<?php }
	if($type=='img'){ ?>
				<button class="btn btn-xs" onclick="$('#attachinput<?=$id?>').click();"  class="attach" data-toggle="tooltip">Select Photo</button>
				<form action="/admin/xhr.php" onsubmit="s.ui.form.upload.file.submit(this,event,'<?=$id?>')" id="upload<?=$id?>" method="post" enctype="multipart/form-data">
					<input name="attach_file" onchange="s.ui.form.upload.file.opensubmit(this,'<?=$id?>')" id="attachinput<?=$id?>" type="file" style="display:none">
					<input type="hidden" name="a" value="media">
					<input type="hidden" name="mediagroupid" value="4">
					<input type="hidden" name="id" value="<?=$id?>">
					<input class="btn btn-xs" type="submit" style="display:none" name="submitUpload" id="submitAttach<?=$id?>" value="Upload" data-toggle="tooltip">
					</form>
			<a class="viewImage" href="<?=$sel[$i]['en']?>">
				<div id="imgView<?=$id?>">
					<img id="img<?=$id?>" class="img-thumbnail" src="<?=!$sel[$i]["en"] ? "/admin/img/post.jpg": urldecode($sel[$i]["en"])?>" style="max-height:150px;">
				</div>
			</a>
			<button class="btn btn-xs" onclick="$('#img<?=$id?>').attr('src',$('#set<?=$id?>').val())">Show me</button>
	<?php } ?>
</div>
<?php }	?>
</div>
<?php }	?>