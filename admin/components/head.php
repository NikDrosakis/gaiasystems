<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="copyright" content="Nik Drosakis">
    <meta name="googlebot" content="all">
    <meta http-equiv="name" content="value">
    <meta name="ROBOTS" CONTENT="NOARCHIVE">
    <meta name="google" content="notranslate">
    <meta name="robots" content="noindex">
    <link rel="stylesheet" href="/admin/css/dashboard.css">
    <script type="text/javascript">var G=<?php echo json_encode($this->G, JSON_UNESCAPED_UNICODE);?>;</script>
    <script src="/admin/js/gaia.js"></script>
    <link rel="icon" href="/admin/img/logo.png">
    <title>Admin > <?=$G['mode']?></title>
<?php if($this->sub=='timetable'){ ?>
<link rel="stylesheet" href="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.css">
<?php } ?>

<!-- JSONEditor -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsoneditor/10.1.0/jsoneditor.min.css" integrity="sha512-8G+Vb2+10BSrSo+wupdzJIylDLpGtEYniQhp0rsbTigPG7Onn2S08Ai/KEGlxN2Ncx9fGqVHtRehMuOjPb9f8g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- CodeMirror JavaScript -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/codemirror.min.css" integrity="sha512-uf06llspW44/LZpHzHT6qBOIVODjWtv4MxCricRxkzvopAlSWnTf6hpZTFxuuZcuNE9CBQhqE0Seu1CoRk84nQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body>
