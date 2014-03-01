<?php

class StmThemeCss extends StmThemeSf {

  /**
   * В этом файле описываются стили, использующие данные $this->oSD->data['data'] для текущего дизайна
   *
   * @var string
   */
  protected $designCssFile;

  /**
   * @var StmCss
   */
  public $css;

  protected $sizes;

  protected function init() {
    $this->css = new StmCss();
    $this->designCssFile = STM_DESIGN_PATH.'/'.$this->data->data['siteSet'].'/'.$this->data->data['design'].'/css.php';
    $this->extendImageUrls();
    $this->initDynamicCss();
    $this->css->addAutoCss($this->data, 'design');
    $this->initStaticCss();
    $this->initMenuCss();
    $this->initMCss();
  }

  protected function initMCss() {
    $folder = STM_THEME_PATH.'/'.$this->data->id.'/m';
    if (file_exists($folder) and is_dir($folder)) {
      $css = file_get_contents($folder.'/css/common/design.css');
      $css = str_replace('/m/', '/'.STM_THEME_WPATH.'/'.$this->data->id.'/m/', $css);
      $this->css->css .= $css;
    }
  }

  protected function extendImageUrls() {
    StmCss::extendImageUrls($this->data);
  }

  protected function initDynamicCss() {
    if (!file_exists($this->designCssFile)) {
      $this->css->addNoFileComment($this->designCssFile);
      return;
    } else {
      $this->css->addCssFile($this->designCssFile, $this->data);
    }
    $this->css->addCssFile(STM_PATH.'/css/pageBlocks/subPages.php', $this->data);
    $this->css->addCssFile(STM_PATH.'/css/content.php', $this->data);
    $this->css->addCssFile(STM_PATH.'/css/slices.php', $this->data);
    $this->css->addCssFile(STM_PATH.'/css/buttons.php', $this->data);
    //$this->css->addCssFile(STM_PATH.'/css/layout.php', $this->data); // колонки
  }

  protected function addStaticCssFile($file) {
    $this->css->addHeaderComments($file);
    $this->css->css .= Misc::getIncluded($file);
  }

  protected function initStaticCss() {
    if ($this->data['data']['black']) {
      $this->addStaticCssFile(STATIC_PATH.'/css/black/icons.css');
      $this->addStaticCssFile(STATIC_PATH.'/css/black/dialog.css');
    }
    $this->addStaticCssFile(STATIC_PATH.'/css/pageBlocks/subPages.css');
  }

  /**
   * @var StmMenuCss
   */
  public $menuCss;

  protected function initMenuCss() {
    if (($menuData = $this->getMenuData()) === false) return;
    $this->menuCss = new StmMenuCss($menuData, $this->data);
    $this->css->css .= $this->menuCss->css->css;
  }

}
