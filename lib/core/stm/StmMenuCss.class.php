<?php

class StmMenuCss {

  /**
   * @var StmMenuData
   */
  protected $menu;

  /**
   * @var StmThemeData
   */
  protected $theme;

  /**
   * @var StmCss
   */
  public $css;

  /**
   * @param StmThemeData Данные меню
   */
  function __construct(StmMenuData $menu, StmThemeData $theme = null) {
    $this->menu = $menu;
    $this->theme = $theme;
    $this->css = new StmCss();
    $this->css->addAutoCss($this->menu, 'menu');
    $this->initDynamicCss();
  }

  protected function initDynamicCss() {
    if (empty($this->theme['menu'])) return; // Если в теме не выбрано меню
    StmCss::extendImageUrls($this->menu);
    $this->menu['menu'] = $this->theme['menu'];
    $this->css->addCssFile(STM_PATH.'/css/menu/paddings.php', $this->menu);
    $this->css->addCssFile(STM_MENU_PATH.'/'.$this->menu->data['data']['menu'].'/css.php', $this->menu);
  }

}