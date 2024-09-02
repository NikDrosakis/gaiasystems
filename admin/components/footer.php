<div id="sidebar" onmousedown="makeResizableRightSidebar(this.id)" onmouseup="makeResizableRightSidebar(this.id)" style="width:<?=isset($_COOKIE['sidebar_width']) ? $_COOKIE['sidebar_width'].'px' : '21%' ?>">

<!--
<div id="activity-widget">
<div id="activities-container">
       <div id="activity-list">

    </div>
<button id="show-more-btn">▼ Show More</button>
</div>
</div>
-->
</div><!---end of sidebar-->

</div><!---end of container-->

<div id="modal" style="display:none;"><div class="modalbg"><div id="modalhead"><a id="modalclose">x</a><span id="modaltitle"></span></div><div id="modalbody"></div><div id="modalfoot"></div></div></div>
<script src="/admin/js/start.js"></script>
</body>
</html>