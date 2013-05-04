<!DOCTYPE html>
<html>
<head>
	<title><?= $data->title ?></title>
  <link rel="stylesheet" type="text/css" href="css/reset.css" charset="utf-8" />
	<link rel="stylesheet" type="text/css" href="css/style.css" media="screen" /> 
	<link rel="stylesheet" type="text/css" href="css/font/stylesheet.css" media="screen" /> 
  <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
  <meta name="description" content="<?= $data->description ?>" />
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>
<body>
<script>
</script>

<?
$i = 1;
foreach($this->data->pages as $page) { 
?>

<div class="page" id="page-<?=$i?>">
  <div style="display: none;">
  <?=page_header($i==1, render_lang($this->lang, $page->header, $page->header_cz), $this->lang, $i)?>
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

<script>
  <?=file_get_contents("./js/main.js")?>
</script>
</body>
</html>
