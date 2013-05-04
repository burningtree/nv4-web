<?php

require './lib/core.php';

$reg_email = 'dmitrij.malysev@gmail.com';

$lang = isset($_GET['lang']) ? $_GET['lang'] : 'en';
if(!in_array($lang, array('cz','en')))
{
  exit;
}

$data = json_decode(file_get_contents("./data.json"));
if(!$data){
  print 'vadny zdrojak.'; exit;
}

$all = array('form_send' => FALSE);

if(isset($_POST['name']) && isset($_POST['email'])){

  $reg_data = array(
    'Datum a cas' => date('j.m.Y H:i'),
    'Jmeno' => sanity($_POST['name']),
    'Email' => sanity($_POST['email']),
    'Pocet listku' => sanity($_POST['tickets']),
    'IP adresa' => $_SERVER['REMOTE_ADDR'],
    'Usergent' => $_SERVER['HTTP_USER_AGENT'],
  );
  $data_arr = array();
  foreach($reg_data as $k=>$v)
  {
    $data_arr[] = "$k: $v";
  }
  mail($reg_email, '[neoviolence] registrace - '.$_POST['name'], join("\n", $data_arr), "From: registrace@neoviolence.com\r\n");
  $all['form_send'] = TRUE;
}

$layout_file = './views/layout.php';
$layout = new Savant3();
$layout->data = $data;
$layout->lang = $lang;
$layout->all = $all;
$layout->display($layout_file);

