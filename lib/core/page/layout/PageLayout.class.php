<?php

class PageLayout {

  static $maxSpan = 24;
  static $minSpan = 5;
  static $spanWidth = 30;

  static function getTypes() {
    return Arr::get(self::getLayouts(), 'descr', 'KEY');
  }
  
  static function getLayouts() {
    $contentCol = [
      'type'        => 'content',
      'allowBlocks' => false
    ];
    $blocksCol = [
      'type'              => 'blocks',
      'allowBlocks'       => true,
      'allowGlobalBlocks' => true
    ];
    $blocksColNoGlobals = [
      'type'              => 'blocks',
      'allowBlocks'       => true,
      'allowGlobalBlocks' => false
    ];
    $layouts = [
      1  => [
        'descr' => 'контент',
        'cols'  => [
          1 => $contentCol + ['span' => 24]
        ]
      ],
      2  => [
        'descr' => 'блоки - контент',
        'cols'  => [
          1 => $blocksCol + ['span' => 5],
          2 => $contentCol + ['span' => 19]
        ]
      ],
      3  => [
        'descr' => 'контент - блоки',
        'cols'  => [
          1 => $contentCol + ['span' => 19],
          2 => $blocksCol + ['span' => 5]
        ]
      ],
      4  => [
        'descr' => 'блоки - контент - блоки',
        'cols'  => [
          1 => $blocksCol + ['span' => 5],
          2 => $contentCol + ['span' => 11],
          3 => $blocksCol + ['span' => 5]
        ]
      ],
      5  => [
        'descr' => 'контент - блоки - блоки',
        'cols'  => [
          1 => $contentCol + ['span' => 14],
          2 => $blocksCol + ['span' => 5],
          3 => $blocksCol + ['span' => 5]
        ]
      ],
      6  => [
        'descr' => 'блоки (NG) - блоки (NG) - блоки (NG)',
        'cols'  => [
          1 => $blocksColNoGlobals + ['span' => 8],
          2 => $blocksColNoGlobals + ['span' => 8],
          3 => $blocksColNoGlobals + ['span' => 8]
        ]
      ],
      7  => [
        'descr' => 'блоки (NG) - блоки (NG) - блоки (NG) - блоки (NG)',
        'cols'  => [
          1 => $blocksColNoGlobals + ['span' => 6],
          2 => $blocksColNoGlobals + ['span' => 6],
          3 => $blocksColNoGlobals + ['span' => 6],
          4 => $blocksColNoGlobals + ['span' => 6]
        ]
      ],
      8  => [
        'descr' => 'блоки (NG) - блоки (NG)',
        'cols'  => [
          1 => $blocksColNoGlobals + ['span' => 12],
          2 => $blocksColNoGlobals + ['span' => 12]
        ]
      ],
      9  => [
        'descr' => 'блоки - контент - блоки',
        'cols'  => [
          1 => $blocksCol + ['span' => 5],
          2 => $contentCol + ['span' => 19],
          3 => $blocksCol + ['span' => 5]
        ]
      ],
      10 => [
        'descr' => 'блоки (NG) - блоки (NG) - блоки (NG)',
        'cols'  => [
          1 => $blocksColNoGlobals + ['span' => 5],
          2 => $blocksColNoGlobals + ['span' => 19],
          3 => $blocksColNoGlobals + ['span' => 5]
        ]
      ],
      11 => [
        'descr' => 'контент - блоки',
        'cols'  => [
          1 => $contentCol + ['span' => 11],
          2 => $blocksCol + ['span' => 10]
        ]
      ],
      12 => [
        'descr' => 'блоки - блоки - блоки - блоки - блоки',
        'cols'  => [
          1 => $blocksCol + ['span' => 24],
          2 => $blocksCol + ['span' => 3],
          3 => $blocksCol + ['span' => 7],
          4 => $blocksCol + ['span' => 7],
          5 => $blocksCol + ['span' => 7]
        ]
      ],
      13 => [
        'descr' => 'блоки (NG) - блоки (NG) - блоки (NG)',
        'cols'  => [
          1 => $blocksColNoGlobals + ['span' => 6],
          2 => $blocksColNoGlobals + ['span' => 12],
          3 => $blocksColNoGlobals + ['span' => 6]
        ]
      ],
      14 => [
        'descr' => 'блоки - контент',
        'cols'  => [
          1 => $blocksCol + ['span' => 6],
          2 => $contentCol + ['span' => 18]
        ]
      ],
      15 => [
        'descr' => 'контент - блоки',
        'cols'  => [
          1 => $contentCol + ['span' => 16],
          2 => $blocksCol + ['span' => 8]
        ]
      ],
      16 => [
        'descr' => 'блоки - контент',
        'cols'  => [
          1 => $blocksCol + ['span' => 8],
          2 => $contentCol + ['span' => 16]
        ]
      ],
      17 => [
        'descr' => 'блоки 8 - контент 10 - блоки 6',
        'cols'  => [
          1 => $blocksCol + ['span' => 8],
          2 => $contentCol + ['span' => 10],
          3 => $blocksCol + ['span' => 6]
        ]
      ]
    ];
    foreach (array_keys($layouts) as $k) {
      $layouts[$k]['n'] = $k;
      $layouts[$k]['allowGlobalBlocks'] = Arr::subValueExists($layouts[$k]['cols'], 'allowGlobalBlocks', true);
    }
    return $layouts;
  }

  static function getColsByLayout($pageId) {
    $layouts = self::getLayouts();
    $layout = $layouts[PageLayoutN::get($pageId)];
    return $layout['cols'];
  }

  static function allowGlobalBlocks($pageId) {
    $layouts = self::getLayouts();
    return $layouts[PageLayoutN::get($pageId)]['allowGlobalBlocks'];
  }

  static function getContentColWidth($pageId) {
    $layouts = self::getLayouts();
    $r = Arr::getValueByKey($layouts[PageLayoutN::get($pageId)]['cols'], 'type', 'content');
    return ($r['span'] * 30) + (($r['span'] - 1) * 10);
  }

  static function autoHtml($layoutN, $pageId, $ctrl) {
    $html = '';
    $cols = self::getLayouts()[$layoutN]['cols'];
    $i = 0;
    foreach ($cols as $n => $col) {
      $i++;
      $class = "span-{$col['span']} col ct_{$col['type']}";
      if ($i == count($cols)) $class .= ' last';
      if ($col['type'] == 'blocks') {
        if (!empty($col['allowBlocks'])) $class .= ' allowBlocks';
        if (!empty($col['allowGlobalBlocks'])) $class .= ' allowGlobalBlocks';
        $blocksHtml = Tt()->getTpl('layout/pageBlocksOneCol', [
          'n'      => $n,
          'blocks' => PageBlockCore::getBlocksByCol($pageId, $n, $ctrl)
        ]);
        $html .= <<<TEXT
<div class="$class" id="col$n" data-n="$n">

    $blocksHtml

</div>
TEXT;
      }
      elseif ($col['type'] == 'content') {
        $submenu = '';
        if (!empty($ctrl->d['submenu'])) {
          $submenu = Tt()->getTpl('common/menu-ul', $ctrl->d['submenu']);
          $submenu = <<<TEXT
<div id="submenu" class="submenu">
  $submenu
</div>
<div class="clear"><!-- --></div>
TEXT;
        }
        $h = '<div class="mainHeader">'. //
          Tt()->getTpl('common/pageTitle', $ctrl->d). //
          Tt()->getTpl('common/pathNav', $ctrl->d). //
          '</div>'.'<div class="mainBody">';
        //if ($ctrl->d['page']['settings']['showSubPages']) $h .= '<div class="subPages">'.Menu::getUlObjById($ctrl->d['page']['id'], 1)->html().'</div>';
        //$h .= Tt()->getTpl($ctrl->d['tpl'], $ctrl->d);
        //if (isset($ctrl->d['content'])) $h .= '<div class="gray">content begins</div>'.$ctrl->d['content'].'<div class="gray">content ends</div>';
        //die2();
        if ($ctrl->d['tpl'] != 'default') {
          $ctrl->d['content'] = Tt()->getTpl($ctrl->d['tpl'], $ctrl->d);
          $ctrl->d['tpl'] = 'default';
        }
        if (isset($ctrl->d['content'])) $h .= $ctrl->d['content'];
        $h .= '</div>';
//        <div class="body moduleBody{$ctrl->d['bodyClass']}">
        $html .= <<<TEXT
<div class="$class" id="col$n" data-n="$n">
  <div class="body moduleBody">
    <div class="bcont">
      $submenu
      $h
    </div>
  </div>
</div>
TEXT;
      }
    }
    return $html;
  }

}
