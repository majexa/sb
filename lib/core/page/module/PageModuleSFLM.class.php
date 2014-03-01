<?php

// работает только при наличии Pmi-класса к модулю

class PageModuleSFLM {
  use Options;

  protected $name, $module, $customWpath = [];
  
  /**
   * Массив точно ссылающийся на файлы $modulePath/$name.$type, в случае их наличия
   * @var array
   */
  protected $static;

  /**
   * Массив, ссылающийся на файлы, полученные из массива в файле $modulePath/$name.php
   * @var array
   * array(
   *   'depends' => 'moduleName',
   *   'css' => array(),
   *   'js' => array()
   * )
   */
  protected $dynamic;
  
  public $wpaths = [
    'css' => [],
    'js' => []
  ];
  
  /**
   * @var PageModuleInfo
   */
  protected $info;

  function __construct($name, $module, array $options = []) {
    $this->options = ['exceptStatic' => false];
    $this->setOptions($options);
    $this->name = $name;
    $this->module = $module;
    $this->info = O::get('PageModuleInfo', $module);
    if (($this->dynamic = $this->info->getData($this->name)) !== false) {
      $this->initDynamic('css');
      $this->initDynamic('js');
      if (isset($this->dynamic['depends'])) {
        $this->wpaths = O::get('PageModuleSFLM', $name, $this->dynamic['depends'], [
          'exceptStatic' => isset($this->dynamic['exceptStatic']) ? $this->dynamic['exceptStatic'] : true
        ])->wpaths;
      }
    }
    if (!$this->options['exceptStatic']) {
      $this->addStaticPath('css');
      $this->addStaticPath('js');
    }
    $this->addDynamicPaths('css');
    $this->addDynamicPaths('js');
  }
  
  protected function initDynamic($type) {
    if (!isset($this->dynamic[$type])) return;
    foreach ($this->dynamic[$type] as &$v) {
      // Если в пути нет слэша, значит этот файл находится в папке текущего модуля
      if (!strstr($v, '/')) $v = $this->info->folderWpath.'/'.$v;
    }
  }
  
  protected function addStaticPath($type) {
    if (($paths = $this->info->getFilePaths("{$this->name}.$type")) === false) return;
    $this->wpaths[$type][] = $paths[1];
  }
  
  protected function addDynamicPaths($type) {
    if (isset($this->dynamic[$type]))
      $this->wpaths[$type] = Arr::append($this->wpaths[$type], $this->dynamic[$type]);
  }
  
  protected function addBlocksPaths($type) {
    // ---
  }
  
  function html() {
    $html = '';
    foreach ($this->wpaths as $type => $wpaths)
      foreach ($wpaths as $wpath)
        $html .= $this->_html($wpath, $type);
    return $html;
  }

  protected function _html($wpath, $type) {
    return $type == 'css' ?
      '<link rel="stylesheet" type="text/css" href="/'.
        $wpath.'?'.BUILD.'" media="screen, projection" />'."\n" :
      '<script src="/'.$wpath.'?'.BUILD.'" type="text/javascript"></script>'."\n";
    ;
  }

}