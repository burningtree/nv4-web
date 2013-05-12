<?php

require_once './vendor/savant/Savant3.php';
require_once './vendor/markdown/Michelf/Markdown.php';
use \Michelf\Markdown;

function render_replate($template, $vars, $all, $lang){

  $fn = './views/'.$template.'.php';
  $tpl = new Savant3();
  $tpl->lang = $lang;
  if(is_object($vars)) foreach($vars as $k=>$v)
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
  if($lang == 'cz' && $content_cz != NULL){
    return $content_cz;
  }

  return $content_en;
}

function page_header($final=FALSE, $txt=NULL, $lang='en', $i = 0, $page_data, $all_datas){
  $symbol = $final ? '&#9661;' : '&#9650;';
  $symbol_class = $final ? 'up' : 'upfill';
  $class = $final ? '' : 'active';


  $footlinks = explode(",", $all_datas->footlinks);
  $footlinks = array_map(function($x){ return trim($x); }, $footlinks);

  $fl_out = array();
  foreach($footlinks as $fl_key => $fl){
    $fl_title = $fl;
    $fli = 1;
    foreach($all_datas->pages as $p){
      if($p->name == $fl){
        $fl_title = (isset($p->name_cz) && $lang=='cz') ? $p->name_cz : $p->name;
        $fl_id = $fli;
        break;
      }
      $fli++;
    }
    $fl_title = ucfirst($fl_title);
    $fl_out[] = $fl == $page_data->name ? $fl_title : "<a href='#".$fl."' onclick='scrollToPage(".$fl_id.", true); return false;'>".$fl_title."</a>";
    $fli++;
  }
  $fl_out = join("&nbsp;&nbsp;&nbsp;\n", $fl_out);

  return <<<EOF
  <div class="footlinks">
    $fl_out
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
  $symbol_class = $final ? 'down' : 'downfill';
  $class = $final ? '' : 'active';
  return <<<EOF
  <div class="footer $class">
    <div class="next">
      <div class="text">$txt</div>
      <div class="ico">$symbol</div>
    </div>
  </div>
EOF;
}
