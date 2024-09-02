<?php if(MAIN_SETUP_EXIST){ ?>
    <h3>Virtual Hosts installed on this system</h3>
    <div id="version"></div>
    <table class="TFtable">
        <tbody>
        <tr class="board_titles">
            <th>Domain</th>
            <th>Type</th>
            <th>DB</th>
        </tr>
        </tbody>
        <?php foreach($tree as $dom => $domarray){ ?>
            <tbody>
            <tr style="<?=SITE==$dom ? "background: yellow":""?>">
                <td><a href="<?=REFERER.$dom?>"><?=$dom?></a></td>
                <td><?=$domarray['type']?></td>
                <td>
                    <form id='<?=implode('',explode('.',$dom))?>' method='get'>
                        <?php foreach($domarray['dbs'] as $i=>$user){	?>
                        <fieldset>
                            <?php foreach($user as $key=>$value){ ?>
                                <label><?=$key?></label><input type='text' name='<?=$key?>"[]' value='<?=$value?>'/><br>
                            <?php	}	?>
                            <?php } ?>
                        </fieldset>
                        <!--<input type='submit' value='Save'/>-->
                        <br/>
                    </form>
                </td>
            </tr>
            </tbody>
        <?php } ?>
    </table>
    <script>var tree=<?=$tree;?></script>
<?php }else{
    echo "No host installed";
} ?>
