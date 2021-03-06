<?php

// работает только при наличии PageModule-класса к модулю

class PageModuleSflm {
  use Options;

  protected $frontendName, $module;

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

  public $wPaths = [
    'css' => [],
    'js'  => []
  ];

  /**
   * @var PageModuleInfo
   */
  protected $info;

  /**
   * @var SflmFrontend
   */
  protected $sflmFrontend;

  /**
   * @param string $frontendName
   * @param $module
   * @param array $options
   */
  function __construct($frontendName, $module, array $options = []) {
    $this->setOptions($options);
    $this->frontendName = $frontendName;
    $this->module = $module;

    $this->info = new PageModuleInfo($module);
    $this->sflmFrontend = Sflm::frontend('js', $this->frontendName);
    $classPaths = $this->sflmFrontend->classes->classPaths;
    $this->sflmFrontend->classes->classPaths = new PageModuleSflmJsClassPaths($module); // пути модуля
    $this->addAction();
    $this->sflmFrontend->classes->classPaths = $classPaths;
  }

  protected function addAction() {
    $this->addStaticPath('css');
    $this->addStaticPath('js');
    if (($this->dynamic = $this->info->getData($this->frontendName)) !== false) {
      $this->initDynamic('css');
      $this->initDynamic('js');
      /*
      if (isset($this->dynamic['depends'])) {
        $this->wPaths = (new self($this->frontendName, $this->dynamic['depends'], [
          //'exceptStatic' => isset($this->dynamic['exceptStatic']) ? $this->dynamic['exceptStatic'] : true
        ]))->wPaths;
      }
      */
      $this->addDynamicPaths('css');
      $this->addDynamicPaths('js');
    }
  }

  protected function initDynamic($type) {
    if (!isset($this->dynamic[$type])) return;
    foreach ($this->dynamic[$type] as &$v) {
      // Если в пути нет слэша, значит этот файл находится в папке текущего модуля
      if (!strstr($v, '/')) $v = $this->info->folderWpath.'/'.$v;
    }
  }

  protected function addStaticPath($type) {
    if (($paths = $this->info->getFilePaths("{$this->frontendName}.$type")) === false) return;
    if ($type == 'js') {
      $this->sflmFrontend->processCode(file_get_contents($paths[0]), 'PageModuleSflm('.$this->module.')::addStaticPath()');
    }
    $this->wPaths[$type][] = $paths[1];
  }

  protected function addDynamicPaths($type) {
    if (!isset($this->dynamic[$type])) return;
    $this->wPaths[$type] = array_merge($this->dynamic[$type], $this->wPaths[$type]);
  }

  function html() {
    $html = '';
    foreach ($this->wPaths as $type => $wPaths) foreach ($wPaths as $wPath) $html .= $this->_html($wPath, $type);
    return $html;
  }

  protected function _html($wPath, $type) {
    return $type == 'css' ? '<link rel="stylesheet" type="text/css" href="/'.$wPath.'" media="screen, projection" />'."\n" : '<script src="/'.$wPath.'" type="text/javascript"></script>'."\n";;
  }

}