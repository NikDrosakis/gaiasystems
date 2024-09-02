<?php
if($this->G['mode']!='' && $this->G['id']!=''){
    include $this->G['SITE_ROOT'] . "main/book_".$this->G['mode'].".php";
// BOOK LIST
}else{
?>
<h2 style="cursor:pointer">Books</h2>
        <div id="books">
            <!--APPEND BOXY OR ARCHIVE STYLE-->
        </div>
        <div id="pagination" class="paginikCon"></div>
<?php } ?>