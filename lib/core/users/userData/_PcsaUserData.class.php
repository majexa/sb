<?php

class PcsaUserData extends Pcsa {
  
  function action($initSettings) {
    $this->setStructures($initSettings, 'profiles');
    $this->setStructures($initSettings, 'ddItemsPages');
    return $initSettings;
  }
  
  protected function setStructures(&$settings, $key) {
    if (empty($settings[$key])) return;
    $pageIds = $settings[$key];
    foreach ($pageIds as $pageId) {
      Misc::checkEmpty($pageId);
      $page = DbModelCore::get('pages', $pageId);
      if (empty($page['strName'])) {
        throw new Exception(
          "Page ID={$page['id']} does not have strName. page:".getPrr($page->r));
      }
      $_settings[] = [
        'pageId' => $page['id'],
        'strName' => $page['strName']
      ];
    }
    $settings[$key] = $_settings;
  }

}