<?php
if($this->G['mode']!='' && $this->G['id']!=''){
    include $this->G['SITE_ROOT'] . "main/book_".$this->G['mode'].".php";
// BOOK LIST
}else{
  ?>
   <h2 style="cursor:pointer">My Library</h2>
   <button type="button" style="border:none;background:none;" id="newbks">New Manual Entry</button>
   <a type="button" style="border:none;background:none;" href="/live">New Live Entry</a>
  <div id="book">
    <!--APPEND BOXY OR ARCHIVE STYLE-->
  </div>
  <div id="pagination" class="paginikCon"></div>
<?php } ?>