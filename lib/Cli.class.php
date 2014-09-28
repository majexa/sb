<?php

class Cli {

  function page($name, $title) {
    PageModule::get($name)->create([
      'title' => $title
    ]);
  }

  function block($pageId, $type) {}

}