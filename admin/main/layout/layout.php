<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js" integrity="sha512-mtMR7axkMEFxsvD/o8ld0Mv3eDKalPggosgTFmZ5NhzQ7fHLzBKNmHqxu9/2wS1vMPN6cFSn1M0sOs6hoL2m5w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<style>
    #areas{
width: 10%;
    max-width: 155px;
float:left;
        display: inline-block;
        background: lightgray;
    }
    #areas > div{
        list-style: none;
        margin: 2%;
        padding: 2px;
        height: 75px;
        border-radius: 10px;
    }
    .unwid{background: wheat}
    #wid{
width: 70%;
    max-width: 800px;        
float: left;
        display: inline-block;
    }
    #wid > div{
        float: left;
        list-style: none;
        margin-bottom: 4px;
        border: 1px solid #d7d7d7;
        border-radius: 10px;
        text-align: center;
        color: #d7d7d7;
    }
    #wid > label {position:absolute}
    #sl, #sr{width:20%;height: 250px !important;}
    #m {width:60%;height: 250px !important;}
    #h,#f {width:100%;}
    #h {height:55px !important;}
    #h1{
        height: 35px;
        border: 1px solid gray;
        width: 100%;
        border-radius:10px;
        background:white;
    }
    #sl1,#sl2,#sl3,#sr1,#sr2,#sr3{
        height: 75px;
        border: 1px solid gray;
        width: 100%;
        border-radius:10px;
        background:white;
    }
    #f{}
    #fr,#fc,#fl{
        height: 75px;
        width: 33%;
        border: 1px solid gray;
        border-radius:10px;
        background:white;
    }
    .widheader{background: aliceblue;font-size: 1.1em;color:#333}
</style>
    <?php
    $selectedpage=isset($_COOKIE['page_selected']) ? $_COOKIE['page_selected'] : "books";
    ?>
    <h2>Layout</h2>
	<p style="float:left">Widgets (Select page and drag & drop widgets to widgetized areas and set details)</p>
		
   <div> Main Page:
	<select style="width:20%" class="form-control" id="page">
        <?php
        $pages=read_folder($this->MAINURI);
        foreach($pages as $pagefile){
            $page=explode('.',$pagefile)[0];
            ?>
            <option value="<?=$page?>" <?=$selectedpage==$page ? "selected='selected'":""?>><?=$page?></option>
        <?php } ?>
    </select></div>
    <!----------------------------------------------------------------------------------
                       WIDGETIZED AREAS
   ----------------------------------------------------------------------------------->
    <div id="wid" class="list-group-item nested-1">
        <!--Header(H)-->
        <div id="h">
            <label>Header H</label>
            <div id="h1" class="droppable">H1</div>
        </div>
        <!--Sidebar Left(SL)-->
        <div id="sl">
            <label>Sidebar Left SL</label>
            <div id="sl1" class="droppable">SL1</div>
            <div id="sl2" class="droppable">SL2</div>
            <div id="sl3" class="droppable">SL3</div>
        </div>

        <!--MAIN(M)-->
        <div id="m">
            <label>Main M</label>
        </div>

        <!--Sidebar Left(SL)-->
        <div id="sr">
            <label>Sidebar Right SR</label>
            <div id="sr1" class="droppable">SR1</div>
            <div id="sr2" class="droppable">SR2</div>
            <div id="sr3" class="droppable">SR3</div>
        </div>
        <!--Footer(F)-->
        <div id="f">
            <label>Footer F</label>
            <div style="display:flex">
            <div id="fl" class="droppable">FL</div>
            <div id="fc" class="droppable">FC</div>
            <div id="fr" class="droppable">FR</div>
            </div>
        </div>

    </div>
    <!----------------------------------------------------------------------------------
                            WIDGETs
    ----------------------------------------------------------------------------------->
    <div id="areas" class="list-group nested-sortable">
        <!--appended loop from localstorage-->

        <?php
        foreach($this->widgets($G) as $wid){
            $wid=explode('.',basename($wid))[0];
            ?>
            <div style="cursor:pointer" class="draggable list-group-item global nested-<?=$wid?> wid" id="<?=$wid?>">
                <div class="widheader"><?=$wid?></div>
                <div style="background:antiquewhite;font-size: 12px;"></div>
            </div>
        <?php } ?>
    </div>


<script src="/admin/main/layout/layout.js"></script>