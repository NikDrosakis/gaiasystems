<div class="row">
    <?php for ($i=0;$i<count($sel);$i++) {
        $postid = $sel[$i]['id'];
        $img = !$sel[$i]['img']
            ?  (strpos($sel[$i]['img_l'], 'http') === 0 ? $sel[$i]['img_l']: "/media/".$sel[$i]['img_l'])
            : (strpos($sel[$i]['img'], 'http') === 0 ? $sel[$i]['img']: "/media/".$sel[$i]['img']); ?>
        <div id="nodorder1_<?=$postid?>"class="card">
            <div class="cover">
                <a style="display:grid;font-size:15px;" href="<?=$sel[$i]['booklink']?>&mode=read"><img id="img<?=$postid?>" src="<?=$img?>"></a>
           </div>
            <div class="description">
                    <span class="published"><a href="/publisher?id=<?=$sel[$i]['publisher']?>&mode=read"><?=$sel[$i]['publishername']?></a>, <?=$sel[$i]['published']?></span>
            </div>
        </div>
        <?php if($sel[$i]['summary']!=null){ ?>
            <div class="card-summary">
            <div class="author"><a href="/writer?id=<?=$sel[$i]['writer']?>&mode=read"><?=$sel[$i]['writername'] != null ? $sel[$i]['writername'] : ''?></a></div>
            <p class="title"><a style="display:grid;color:#000000;font-size:15px;" href="<?=$sel[$i]['booklink']?>"><?=$sel[$i]['title']?></a></p>
            <?=$sel[$i]['summary']?></div>
        <?php } ?>
    <?php } ?>
</div>