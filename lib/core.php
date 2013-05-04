<?php

require_once './vendor/savant/Savant3.php';
require_once './vendor/markdown/Michelf/Markdown.php';
use \Michelf\Markdown;

function render_replate($template, $vars, $all, $lang){

  $fn = './views/'.$template.'.php';
  $tpl = new Savant3();
  $tpl->lang = $lang;
  foreach($vars as $k=>$v)
  {
    $czkey = $k.'_cz';
    if($lang == 'cz' && isset($vars->$czkey)){
      $v = $vars->$czkey;
    }
    $tpl->$k = Markdown::defaultTransform($v);
  }
  foreach($all as $k=>$v)
  {
    $tpl->$k = $v;
  }

  $tpl->display($fn);
}


function render_content($content, $md=TRUE){
  if($md){
    return Markdown::defaultTransform($content);
  }
  return nl2br($content);
}

function render_lang($lang, $content_en, $content_cz)
{
  if($lang == 'cz' && $content_cz != NULL)
    return $content_cz;
  
  return $content_en;
}

function page_header($final=FALSE, $txt=NULL, $lang='en', $i = 0, $page_data){
  $symbol = $final ? '&#9661;' : '&#9650;';
  $class = $final ? '' : 'active';

  switch($lang){
    case 'cz':
      $lang_name = 'English';
      $lang_out = 'en';
      $lang_url = '/';
      break;
    case 'en':
      $lang_name = 'Česky';
      $lang_url = '/cz/';
      break;
  }

  return <<<EOF
  <div class="lang">
    <a href="$lang_url#$page_data->name">$lang_name</a>&nbsp;&nbsp;&nbsp;
    <a href="http://www.facebook.com/events/142243002620793/">Facebook</a>
  </div>
  <div class="header $class">
    <div class="ico">$symbol</div>
    <div class="text">$txt</div>
  </div>
EOF;
}

function sanity($txt){
  return trim(strip_tags($txt));
}


function page_footer($final=FALSE, $txt=NULL){
  $symbol = $final ? '&#9651' : '&#9660;';
  $class = $final ? '' : 'active';
  return <<<EOF
  <div class="footer $class">
    <div class="text">$txt</div>
    <div class="ico">$symbol</div>
  </div>
EOF;
}
