<?php

class StmCss {

  public $css = '';

  protected function addComment($s) {
    if (IS_DEBUG !== true) return;
    $this->css .= $s;
  }

  function addNoFileComment($file) {
    $this->addComment("\n/*======== File '$file' does not exists ========*/\n");
  }

  function addNoDataComment($v) {
    $this->addComment("\n/*======== No data: $v ========*/\n");
  }

  function addHeaderComments($title) {
    $this->addComment("\n\n/*======== $title =======*/\n\n");
  }

  function addAutoCss(StmData $data, $name = null) {
    if (empty($data->data['cssData'])) return;
    $this->addHeaderComments($name.' auto css');
    $d = [];
    foreach ($data->data['cssData'] as $v) {
      $d[$v['s']][$v['p']] = $v['v'];
    }
    foreach ($d as $selector => $params) {
      $this->css .= "\n".$selector." {\n".Tt()->enum($params, "\n", '$k.`: `.$v.`;`')."\n}\n";
    }
  }

  function addCssFile($file, StmData $data = null) {
    $this->addHeaderComments(str_replace('.php', '', str_replace(STM_PATH, '', $file)));
    $data = $data->data['data'];
    if (isset($data->data['cssData'])) $data['cssData'] = $data->data['cssData'];
    Err::noticeSwitch(false);
    $this->css .= Misc::getIncluded($file, $data);
    Err::noticeSwitchBefore();
  }

  static $propFields = [
    'fontSize'               => [
      'title' => 'Размер шрифта',
      'type'  => 'fontSize'
    ],
    'fontSize2'              => [
      'title' => 'Размер шрифта меню 2-го уровня',
      'type'  => 'fontSize'
    ],
    'submenuWidth'           => [
      'title'    => 'Ширина меню 2-го уровня',
      'required' => true,
      'type'     => 'num',
    ],
    'marginRight'            => [
      'title' => 'Отступ справа'
    ],
    'fontFamily'             => [
      'title' => 'Шрифт',
      'type'  => 'fontFamily',
      's'     => '.mainmenu',
      'p'     => 'font-family',
    ],
    'fontStyle'              => [
      'title' => 'Наклонный',
      'type'  => 'fontStyle',
      's'     => '.mainmenu',
      'p'     => 'font-style',
    ],
    'menuSize'               => [
      'title'    => 'Размер меню',
      'type'     => 'select',
      'required' => true,
      'options'  => [
        'sm' => 'Маленький',
        'md' => 'Средний',
        'lg' => 'Большой'
      ]
    ],
    'menuHeight'             => [
      'title' => 'Высота меню',
      'type'  => 'num'
    ],
    'itemHeight'             => [
      'title' => 'Высота ячейки меню'
    ],
    'spanMarginTop'          => [
      'title' => 'Отступ от ячейки меню до верхнего края меню'
    ],
    'fontWeight'             => [
      'title'   => 'Жирность',
      'type'    => 'select',
      'options' => [
        'normal' => 'нормальный',
        'bold'   => 'жирный'
      ]
    ],
    'fontWeight2'            => [
      'title'   => 'Жирность меню 2-го уровня',
      'type'    => 'select',
      'options' => [
        'normal' => 'нормальный',
        'bold'   => 'жирный'
      ]
    ],
    'bgColor'                => [
      'title' => 'Фон ячейки меню',
      'type'  => 'color'
    ],
    'bgColorActive'          => [
      'title' => 'Фон активной ачейки меню',
      'type'  => 'color',
    ],
    'bgColorOver'            => [
      'title' => 'Фон ячейки меню с наведеной мышью',
      'type'  => 'color',
    ],
    'bgColor2'               => [
      'title' => 'Фон ячейки меню 2 уровня',
      'type'  => 'color'
    ],
    'bgColorActive2'         => [
      'title' => 'Фон активной ачейки меню 2 уровня',
      'type'  => 'color',
    ],
    'bgColorOver2'           => [
      'title' => 'Фон ячейки меню 2 уровня с наведеной мышью',
      'type'  => 'color',
    ],
    'colorActive'            => [
      'title' => 'Цвет шрифта активной ячейки меню',
      's'     => '.mainmenu .active a',
      'p'     => 'color',
      'type'  => 'color'
    ],
    'backgroundImage'        => [
      'title' => 'Фоновое изображение',
      'type'  => 'image'
    ],
    'borderColor'            => [
      'title' => 'Цвет бордюра',
      'type'  => 'color'
    ],
    'color'                  => [
      'title' => 'Цвет шрифта неактивной ячейки меню',
      's'     => '.mainmenu a',
      'p'     => 'color',
      'type'  => 'color'
    ],
    'color2'                 => [
      'title' => 'Цвет шрифта неактивной ячейки меню 2-го уровня',
      'type'  => 'color'
    ],
    'colorHover'             => [
      'title' => 'Цвет шрифта при наведении мыши',
      's'     => '.mainmenu a:hover',
      'p'     => 'color',
      'type'  => 'color'
    ],
    'radius'                 => [
      'title' => 'Радиус загругления углов',
      'type'  => 'num'
    ],
    'textMarginTop'          => [
      'title' => 'Отступ сверху от текста меню 1-го уровня',
      's'     => '.mainmenu > ul > li > a span',
      'p'     => 'padding-top',
      'type'  => 'pixels'
    ],
    'itemMarginRight'        => [
      'title' => 'Отступ от ячейки меню справа',
      's'     => '.mainmenu > ul li',
      'p'     => 'margin-right',
      'type'  => 'pixels'
    ],
    'menuBorderWidth'        => [
      'title' => 'Размер бордюра меню',
      'type'  => 'num'
    ],
    'submenuBorderWidth'     => [
      'title' => 'Размер разделителей меню 2-го уровня',
      'type'  => 'num'
    ],
    'submenuBorderColor'     => [
      'title' => 'Цвет разделителей меню 2-го уровня',
      'type'  => 'color'
    ],
    'roundBorderColor'       => [
      'title' => 'Цвет закругленного бордюра',
      'type'  => 'color'
    ],
    'roundBorderColorOver'   => [
      'title' => 'Цвет закругленного бордюра при наведении мыши',
      'type'  => 'color'
    ],
    'roundBorderColorActive' => [
      'title' => 'Цвет закругленного бордюра активной ячейки',
      'type'  => 'color'
    ],
    'linkImage'              => [
      'title' => 'Изображение фона обычной ссылки меню',
      'type'  => 'image'
    ],
    'linkImageHover'         => [
      'title' => 'Изображение фона обычной ссылки меню при наведении',
      'type'  => 'image'
    ],
    'linkSeparatorImage'     => [
      'title' => 'Изображение разделителя по горизонтали для ячеек первого уровня',
      'type'  => 'image'
    ],
    'linkImageActive'        => [
      'title' => 'Изображение фона активной ссылки меню',
      'type'  => 'image'
    ],
    'columnWidth'            => [
      'title' => 'Ширина колонки',
      's'     => '.mainmenu li',
      'p'     => 'width',
      'type'  => 'pixels'
    ],
    'menuWidth'              => [
      'title' => 'Ширина меню',
      's'     => '.mainmenu',
      'p'     => 'width',
      'type'  => 'pixels'
    ],
    'barColor'               => [
      'title' => 'Цвет панели меню',
      'type'  => 'color'
    ]
  ];

  /**
   * Theme static files folder name
   *
   * @var string
   */
  const FOLDER_NAME = 'i';

  static function extendImageUrls(StmData $data) {
    foreach ($data->getStructure()->str['fields'] as $f) {
      if (isset($f['type']) and $f['type'] == 'image') {
        if (!empty($data[$f['name']])) {
          if (is_bool($data[$f['name']])) $data[$f['name']] = "{$f['name']}.png";
          list($w, $h) = getimagesize($data->getThemePath().'/'.self::FOLDER_NAME.'/'.$data->getName().'/'.$data[$f['name']]);
          $data[$f['name']] = [
            'url' => '/'.$data->getThemeWpath().'/'.self::FOLDER_NAME.'/'.$data->getName().'/'.$data[$f['name']],
            'w'   => $w,
            'h'   => $h
          ];
        }
      }
    }
  }

  static function cleanColors(StmData $oSD) {
    foreach (Arr::filterByValue($oSD->getStructure()->str['fields'], 'type', 'color') as $v) {
      if (isset($oSD->data['data'][$v['name']])) $oSD->data['data'][$v['name']] = str_replace('#', '', $oSD->data['data'][$v['name']]);
    }
  }

  static function url($url, $ext = 'png') {
    // Очистка параметров цветов
    $url = preg_replace('/\/#/', '/', $url);
    return UrlCache::get($url, $ext);
  }

}
