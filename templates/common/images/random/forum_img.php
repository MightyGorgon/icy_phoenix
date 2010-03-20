<?php
$bg_id = (isset($_GET['f']) ? intval($_GET['f']) : (isset($_POST['f']) ? intval($_POST['f']) : 0));
$bg_id = ($f < 0) ? 0 : $bg_id;

$img = 'bg' . $bg_id . '.jpg';
$img = file_exists($img) ? $img : 'bg0.jpg';

header('Content-type: image/png');
header('Content-Disposition: filename=' . $img);
readfile($img);

// Alternatively you may want to use this
//header('Location: http://www.example.com/' . $img_array[$img_rnd]);

?>