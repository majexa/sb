<?php

$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__), RecursiveIteratorIterator::SELF_FIRST);
foreach ($objects as $name => $object) {
  if (is_dir($object)) continue;
  if (!strstr($object, 'Pmi')) continue;
  $new = str_replace('Pmi', 'PageModule', $object);
  //print "$new\n";
  rename($object, $new);
}