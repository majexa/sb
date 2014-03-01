<?php

class StmThemeDataSet extends Options2 {

  public $items = [];
  public $requiredOptions = ['siteSet'];
  
  function init() {
    foreach (StmLocations::getThemeFolders() as $location => $themeFolder) {
      foreach (Dir::dirs($themeFolder.'/'.$this->options['siteSet']) as $design) {
        $oSDS = new StmDataSource([
          'location' => $location,
          'siteSet' => $this->options['siteSet'],
          'design' => $design
        ]);
        foreach (Dir::files($themeFolder.'/'.$this->options['siteSet'].'/'.
        $design.'/data') as $id) {
          $id = str_replace('.php', '', $id);
          $this->items[] = new StmThemeData($oSDS, ['id' => $id]);
        }
      }
    }
  }
  
  function getOptions() {
    $options = [];
    foreach ($this->items as $v) {
      /* @var $v StmThemeData */
      $options[$v->source->options['location'].':'.$v->source->options['design'].':'.$v->id] =
        $v->source->structure['title'].' / '.$v->data['data']['title'];
    }
    return $options;
  }
  

}
