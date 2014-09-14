<?php

$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__), RecursiveIteratorIterator::SELF_FIRST);
function replace($s) {
  return str_replace('SubPa', 'SubCtrl', $s);
}
foreach ($objects as $name => $object) {
  if (is_dir($object)) continue;
  if (!strstr($object, 'SubPa')) continue;
  $new = replace($object);
  //print("$object, $new\n");
  file_put_contents($object, replace(file_get_contents($object)));
  rename($object, $new);
}