<?php

if (!defined('DATA_PATH')) throw new Exception('Site Theme cannot be loaded before set of DATA_PATH constant');

define('STM_WPATH', 'stm');
define('STM_PATH', dirname(NGN_PATH).'/'.STM_WPATH);

define('STM_DESIGN_WPATH', STM_WPATH.'/design');
define('STM_DESIGN_PATH', STM_PATH.'/design');

define('STM_MENU_WPATH', STM_WPATH.'/menu');
define('STM_MENU_PATH', STM_PATH.'/menu');

define('STM_THEME_WPATH', STM_WPATH.'/themes');
define('STM_THEME_PATH', STM_PATH.'/themes');

define('STM_DATA_PATH', DATA_PATH.'/stm');

class StmCore {

  // ------------------ template functions ------------------

  static function menu($pageName = 'main', $linkDddd = '`<a href="`.$link.`"><span>`.$title.`</span></a><i></i><div class="clear"></div>`') {
    $data = self::getCurrentMenuData();
    return Menu::ul($pageName, $data ? $data->data['data']['levels'] : 1, $linkDddd);
  }

  static function slices() {
    if (($data = self::getCurrentThemeData()) === false) return '';
    if (($slices = $data->data['data']['slices']) == null) return;
    $html = '';
    foreach ($slices as $v) $html .= Slice::html($v['id'], $v['title'], [
      'absolute'   => true,
      'allowAdmin' => $v['allowAdmin']
    ]);
    return $html;
  }

  // --------------------------------------------------------

  static function getCurrentThemeParams() {
    if (($theme = Config::getVarVar('theme', 'theme', true)) === false) return false;
    if (empty($theme)) return false;
    if ($theme == ':') return false;
    return explode(':', $theme);
  }

  static function enabled() {
    return Config::getVarVar('theme', 'enabled', true);
  }

  /**
   * Возвращает объект данных для текущей темы сайта
   *
   * @return StmThemeData
   */
  static function getCurrentThemeData() {
    if (($r = self::getCurrentThemeParams()) === false) return false;
    list($location, $id) = $r;
    return (new StmThemeData(new StmDataSource($location), ['id' => $id]))->init();
  }

  /**
   * @return StmMenuData
   */
  static function getCurrentMenuData() {
    if (($r = self::getCurrentThemeParams()) === false) return false;
    list($location, $id) = $r;
    $data = new StmMenuData(new StmDataSource($location), ['id' => $id]);
    return $data->exists() ? $data->init() : false;
  }

  /**
   * @return StmThemeStructure
   */
  static function getThemeStructure($siteSet, $design) {
    return O::get('StmThemeStructure', [
      'siteSet' => $siteSet,
      'design'  => $design
    ]);
  }

  /**
   * @return StmMenuStructure
   */
  static function getMenuStructure($menuType) {
    return O::get('StmMenuStructure', [
      'menuType' => $menuType,
    ]);
  }

  static function getMenuStructures() {
    $r = [];
    foreach (Dir::dirs(STM_MENU_PATH) as $v) {
      $r[$v] = include STM_MENU_PATH.'/'.$v.'/structure.php';
    }
    return $r;
  }

  /**
   * @param string   Пример: ngn:12
   */
  static function updateCurrentTheme($theme) {
    SiteConfig::updateSubVar('theme', 'enabled', true);
    SiteConfig::updateSubVar('theme', 'theme', $theme);
  }

  static function getTags() {
    if (!Config::getVarVar('theme', 'enabled', true) or !Config::getVarVar('theme', 'theme')) return '';
    return '<link rel="stylesheet" type="text/css" media="screen, projection" href="/'.(empty($_GET['theme']) ? Sflm::frontend('css')->sflm->getCachedUrl('s2/css/common/theme').'?'.BUILD : 's2/css/common/theme?'.http_build_query($_GET['theme'])).'" />'.'<script type="text/javascript" src="/'.(empty($_GET['theme']) ? Sflm::frontend('js')->getCachedUrl('s2/js/common/theme').'?'.BUILD : 's2/js/common/theme?'.http_build_query($_GET['theme'])).'"></script>';
  }

  static function cc() {
    Sflm::frontend('css')->clearPathCache('s2/css/common/theme');
    Sflm::frontend('js')->clearPathCache('s2/js/common/theme');
  }

}
