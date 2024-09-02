<?php for($i=0;$i<count($sel);$i++) { 
$id = $sel[$i]['id'];
?>
<div class="widget-box">
    <div class="widget-header">
		<label style="color:darkblue">ID:<?=$sel[$i]['id']?></label>
        <span class="widget-version">v<?=$sel[$i]['version']?></span>
        <span class="widget-version">Systems Used:<?=$sel[$i]['systems_used']?></span>
        <span class="widget-version">Layout Views:<?=$sel[$i]['layout_views']?></span>
        <span class="widget-version">Total Duration:<?=$sel[$i]['total_duration']?></span>
	</div>
    <label style="color:darkblue" for="widget-name-<?=$id?>"><?=$sel[$i]['name']?></label>

    <label style="display:block;clear:left" for="widget-status-<?=$id?>">Status:</label>
    <select id="widget-status-<?=$id?>" class="widget-select">
        <?php foreach($params['statuses'] as $statusid => $status){  ?>
            <option value="<?=$statusid?>" <?=$statusid==$sel[$i]['status'] ? 'selected=selected':''?> ><?=$status?></option>
        <?php } ?>
    </select>

    <label style="display:block;clear:left" for="widget-description-<?=$id?>">Description:</label>
    <textarea id="widget-description-<?=$id?>" class="widget-textarea" placeholder="Enter description"><?=$sel[$i]['description']?></textarea>

    <label style="display:block;clear:left" for="widget-valuability-<?=$id?>">Valuability (1-10):</label>
    <input type="number" id="widget-valuability-<?=$id?>" class="widget-input" value="<?=$sel[$i]['valuability']?>">

    <label style="display:block;clear:left" for="widget-flag-<?=$id?>">Flag:</label>
    <select id="widget-flag-<?=$id?>" class="widget-select">
        <option value="0" <?=$sel[$i]['flag'] == 0 ? 'selected=selected':''?> >Off</option>
        <option value="1" <?=$sel[$i]['flag'] == 1 ? 'selected=selected':''?> >On</option>
    </select>

    <label style="display:block;clear:left" for="widget-ideally-<?=$id?>">Ideally:</label>
    <textarea id="widget-ideally-<?=$id?>" class="widget-textarea" placeholder="Enter ideally"><?=$sel[$i]['ideally']?></textarea>

	<button class="button" id="delete-widget-<?=$id?>">Delete widget</button>
</div>
<?php } ?>
