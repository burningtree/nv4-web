<?php

$file = "./data.json";
if(isset($_POST) && isset($_POST['description'])){

  $encoded = json_encode($_POST, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
  if($encoded){
    file_put_contents($file, $encoded);
    print '<div style="color:red">Data saved!</div>';
  }
}

$data = json_decode(file_get_contents($file));

?>
<style>
  textarea { width: 1000px; height: 200px; }
  .red td { color: red; }
</style>

<form action="editor.php" method="POST">
<table>

<?

function print_tr($key, $value, $new=FALSE){

  if(!is_string($value))
    return null;

  if($new){
    $class = "red";
  }

  $val = nl2br($value);
  $text = <<<EOF
  <tr class="$class">
  <td>$key></td>
  <td><textarea name="$key">$value</textarea></td>
  </tr>

EOF;

  return $text;
}

foreach($data as $key=>$value){

  $last = array();
  if(is_array($value)){
    $ip = 1;
    foreach($value as $i=>$page){
      foreach($page as $k2 => $v2){

        if($v2 instanceof stdClass){
          foreach($v2 as $k3 => $v3){
            print print_tr($key.'['.$i.']['.$k2.']['.$k3.']', $v3);
          }
  
        } else {
          print print_tr($key.'['.$i.']['.$k2.']', $v2);

          if(count($value) == $ip){
            $last[] = '<tr><td><a id="new-'.$k2.'"></a></td></tr>'.print_tr($key.'['.($i+1).']['.$k2.']', '', TRUE);
          }
        }
      }
      $ip++;
    }
    if(isset($_GET['newpage'])){
      print join("\n", $last);
    }
  } else {
    print print_tr($key, $value);
  }
}
?>

</table>

<input type="submit" value="Save" />

<?
    if(isset($_GET['newpage'])){
?>

<a href="?">no new page</a>
<?  } else {?>

<a href="?newpage#new">Add new page</a>
<? }?>

</form>
