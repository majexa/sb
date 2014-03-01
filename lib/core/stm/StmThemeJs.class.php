<?php

class StmThemeJs extends StmThemeSf {

  public $js = '';
  
  protected function init() {
    $this->initHomeOffset();
    $this->initMenu();
    $this->initCart();
  }
  
  protected function initHomeOffset() {
    if (empty($this->data['data']['homeTopOffset'])) return;
    $y = (int)$this->data['data']['homeTopOffset'];
    $this->js .= "
if (!Ngn.layout) Ngn.layout = {};
Ngn.layout.homeTopOffset = $y;
";
  }
  
  protected function initMenu() {
    if (($oMD = $this->getMenuData()) === false) return;
    if (!file_exists(STM_MENU_PATH.'/'.$oMD->menuType.'/js.php')) return;
    $this->js .= Misc::getIncluded(STM_MENU_PATH.'/'.$oMD->menuType.'/js.php', $oMD->data['data']);
    $oThemeCss = new StmThemeCss($this->data);
    if ($oThemeCss->menuCss) $this->js .= CssCore::getProloadJs($oThemeCss->menuCss->css->css);
  }
  
  protected function initCart() {
    if (!Config::getVarVar('store', 'enable')) return;
    if (empty($this->data['data']['slices']) or
      Arr::getValueByKey($this->data['data']['slices'], 'id', 'cart') === false) return;
    $opt = Arr::jsObj([
      'storeOrderController' => StoreCore::getOrderController()
    ]);
    $this->js .= "
window.addEvent('domready',function() {
  Ngn.cart.initBlock($('slice_cart').getElement('.slice-text'), $opt);
});
";
  }
  
}