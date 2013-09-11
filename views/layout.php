<!DOCTYPE html>
<html>
<head>
	<title><?= $this->data->title ?></title>
  <link rel="stylesheet" type="text/css" href="/css/reset.css" />
	<link rel="stylesheet" type="text/css" href="/css/style.css" media="screen" /> 
	<link rel="stylesheet" type="text/css" href="/css/custom.css" media="screen" /> 
	<link rel="stylesheet" type="text/css" href="/css/font/stylesheet.css" media="screen" /> 
  <link rel="icon" type="image/ico" href="/favicon.ico" />
  <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
  <meta name="description" content="<?= $this->data->description ?>" />
  <meta name="author" content="Jan Stránský <jan.stransky@arnal.cz>">
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <meta property="og:title" content="<?= $this->data->title ?>"/> 
  <meta property="og:url" content="<?= $this->data->url ?>"/> 
  <meta property="og:description" content="<?= $this->data->description ?>"/> 
  <meta property="og:image" content="<?= $this->data->poster ?>"/> 
</head>
<body>

<div id="mainframe">
  <div class="bg"></div>
  <div class="nvlogo"></div>
  <? if($this->data->help_enable == TRUE): ?>
    <div id="help" style="display:none;"><p><?= render_lang($this->lang, $this->data->help, $this->data->help_cz) ?></p></div>
  <? endif; ?>

<?php
  switch($this->lang){
    case 'cz':
      $lang_name = 'English';
      $lang_out = 'en';
      $lang_url = '/en/';
      break;
    case 'en':
      $lang_name = 'Česky';
      $lang_url = '/';
      break;
  }
?>

  <div class="lang">
    <a href="<?= $lang_url ?>#<?= $page_data->name ?>" onclick="document.location='<?=$lang_url?>'+document.location.hash; return false;"><?=$lang_name?></a>&nbsp;&nbsp;&nbsp;
    <a href="<?= $this->data->facebook_url ?>"><?= render_lang($this->lang, $this->data->facebook_title, $this->data->facebook_title_cz)?></a>
  </div>

<?
$i = 1;
foreach($this->data->pages as $page) { 
?>

<div class="page num-<?=$i?> nonbg" id="<?=$page->name?>" data-page="<?=$i?>">
  <div style="display: none; width: 100%;">
  <?=page_header($i==1, render_lang($this->lang, $page->header, $page->header_cz), $this->lang, $i, $page, $this->data)?>
  <div class="center">
    <div class="inner">
      <?= isset($page->view) ? render_replate($page->view, $page->view_data, $this->all, $this->lang) : render_content(render_lang($this->lang, $page->content, $page->content_cz), TRUE) ?>
    </div>
  </div>
  <?=page_footer(count($this->data->pages) == $i, render_lang($this->lang, $page->footer, $page->footer_cz))?>
  </div>
</div>

<? 
$i++; 
} 
?>
</div>

<script>
  <?//=file_get_contents("./js/jquery.parallax.min.js")?>

  var pagesCount = <?= count($this->pages_map) ?>;
  var pagesMap = <?= json_encode($this->pages_map) ?>;
  var defaultPage = <?= $this->data->default_page ?>;
  <?=file_get_contents("./js/main.js")?>
</script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', '<?= $this->data->ganalytics_code ?>', '<?= $this->data->ganalytics_domain ?>');
  ga('send', 'pageview');

</script>
</body>
</html>
