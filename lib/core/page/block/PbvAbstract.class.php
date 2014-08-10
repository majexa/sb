<?php

/**
 * Page Block View
 */
abstract class PbvAbstract {

  static $cachable = false;

  /**
   * @var array
   */
  protected $pageBlock;

  /**
   * Current Controller
   *
   * @var CtrlPage
   */
  public $ctrl;

  protected $cssClass = '';

  protected $extendImageData = false;

  /**
   * @param null|array|DbModel $pageBlock
   * @param CtrlPage $ctrl
   */
  function __construct($pageBlock = null, CtrlPage $ctrl = null) {
    $this->pageBlock = $pageBlock;
    $this->extendImageData();
    $this->ctrl = $ctrl;
    $this->init();
    $this->initButtons();
  }

  protected function initButtons() {
  }

  protected function extendImageData() {
    if (!$this->pageBlock) return;
    if (!$this->extendImageData) return;
    $this->pageBlock['settings'] = DataManagerAbstract::extendImageData($this->pageBlock['settings'], PageBlockCore::getStructure($this->pageBlock['type'])->getFields());
  }

  protected function init() {
  }

  function styles() {
    return [];
  }

  function _html() {
    $html = Tt()->getTpl('pageBlocks/'.ClassCore::classToName('Pbv', get_class($this)), $this->pageBlock['settings']);
    $html = preg_replace('/(<img[^>]+src=")([^"]+)("[^>]*>)/', '$1$2'.'?'.md5($this->pageBlock['dateUpdate']).'$3', $html);
    return $html;
  }

  protected $moreLink;
  protected $buttons = [];
  protected $js = '';

  function html() {
    Sflm::frontend('js')->addClass('Ngn.Pb.BlockEdit.'.ucfirst($this->pageBlock['type']), get_class($this).'::html()', false);
    $titleHtml = '';
    if (isset($this->moreLink)) $titleHtml .= '<a href="'.$this->moreLink['link'].'" class="hbtn small"><span>'.$this->moreLink['title'].'</span></a>';
    if ($this->buttons) {
      $titleHtml .= '<div class="smIcons bordered">';
      foreach ($this->buttons as $v) {
        $titleHtml .= '<a href="'.$v['link'].'" title="'.$v['title'].'" class="'.$v['class'].'"><i></i></a>';
      }
      $titleHtml .= '</div>';
    }
    $html = '';
    if ($titleHtml or !empty($this->pageBlock['settings']['title'])) {
      $html .= '<div class="btitle">'.$titleHtml.($this->pageBlock['settings']['title'] ? '<h2>'.$this->pageBlock['settings']['title'].'</h2>' : '').'</div>';
    }
    $html .= '<div class="bbody">'.$this->_html().'</div>';
    return $html;
  }

  /**
   * Возвращает готовые данные для отображения блока (html и стили)
   */
  function getData() {
    $r = $this->pageBlock->r;
    return array_merge($r, [
      'html'   => $this->html(),
      'class'  => $this->cssClass,
      'styles' => $this->styles()
    ]);
  }

}