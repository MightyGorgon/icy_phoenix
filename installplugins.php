<?php
error_reporting(-1);

function array_last($xs) { return $xs[count($xs) - 1]; }
define('PROPAGATE', isset($_GET['propagate']));
define('INSTALL', !PROPAGATE); // if we want to install (cp to ip) or apply changes (cp back to plugins)

echo '<pre>';
echo "echo MODE : " . (INSTALL ? "INSTALL" : "PROPAGATE") . "\n";

$dir = '../icy_phoenix_plugins';
foreach (glob($dir.'/*') as $plugin_dir) {
  if (!is_dir($plugin_dir)) continue;
  $plugin = array_last(explode('/', $plugin_dir));
  $plugin_root = "$plugin_dir/ip_root/";
  echo "echo $plugin -\\> $plugin_root"."\n";
  mirror_to($plugin_root, ".", strlen($plugin_root));
}

$chmod = [
  777 => ['backup', 'cache', 'cache/cms', 'cache/forums', 'cache/posts', 'cache/sql', 'cache/topics', 'cache/uploads', 'cache/users', 'downloads', 'files', 'files/album', 'files/album/cache', 'files/album/med_cache', 'files/album/users', 'files/album/wm_cache', 'files/images', 'files/screenshots', 'files/thumbs', 'files/thumbs/s', 'images/avatars'],
  666 => ['logs', 'logs/logfile_attempt_counter.txt', 'logs/logfile_blocklist.txt', 'logs/logfile_debug_mode.txt', 'logs/logfile_malformed_logins.txt', 'logs/logfile_spammer.txt', 'logs/logfile_worms.txt'],
];
foreach ($chmod as $mode => $files) {
  echo "chmod 0$mode ".implode(' ', $files)."\n";
}
echo "</pre>";

function mirror_to($dir, $to, $strip, $i = 0) {
 #echo str_repeat('&nbsp;', 10 * $i) . 'mirror ' .$dir.' to '.$to.'<br>';
  foreach (glob($dir."*") as $file) {
    $stripped_name = substr($file, $strip);
   #echo str_repeat('&nbsp;', 10 * ($i + 1)) . $stripped_name . '<br>';
   #var_dump($to, $stripped_name);
    if (is_dir($file) && file_exists($stripped_name)) {
      mirror_to($file."/", $stripped_name, $strip, $i + 1);
    } else {
     $mode = PROPAGATE ? "$stripped_name $file" : "$file $stripped_name";
     $r = is_dir($file) ? "-r " : "";
     echo "cp $r$mode\n";

     #$dir = dirname($stripped_name);
     #$base = basename($stripped_name);
     #$pre = str_repeat('../', substr_count($stripped_name, "/"));
     #echo "cd $dir && unlink $base; ln -s $pre$file $base 2>&1; cd -\n";
    }
  }
}

