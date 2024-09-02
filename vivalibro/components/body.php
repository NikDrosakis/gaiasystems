<body class="<?=!empty($_COOKIE['display']) ? $_COOKIE['display']:'light'?>">
<!--
{"sl1":"wid-similar","sl2":"wid-top","sl3":"wid-notification","sr1":"wid-summary","sr2":"wid-slideshow","sr3":null,"fl":null,"fc":null,"fr":null}
-->
<!--header-->
<div id="h"></div>

<!--sidebar left-->
<div id="sl"></div>

<!----MAIN--->
<div id="main">
<?php include "main/".$this->G['page'].'.php'; ?>
</div>

<!-------sidebar right--->
<div id="sr"></div>

<!-------footer--->
<div id="f"></div>

<div id="modal" style="display:none;"><div class="modalbg"><div id="modalhead"><a id="modalclose">x</a><span id="modaltitle"></span></div><div id="modalbody"></div><div id="modalfoot"></div></div></div>

<script src="/js/load.js"></script>
</body>
</html>

