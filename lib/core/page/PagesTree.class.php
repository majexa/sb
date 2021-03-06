<?php

class PagesTree extends ClientTree {

  function __construct() {
    $this->allowedDataParams = array_merge($this->allowedDataParams, ['folder', 'title', 'path', 'controller']);
    parent::__construct(new PagesDbTree);
  }

  protected function setNodeType(array &$node, array $data) {
    $node['type'] = !empty($data['folder']) ? 'folder' : 'page';
  }

  protected function setNodeCls(array &$node, array $data) {
    $node['property']['cls'] = trim(Misc::cleanupSpaces(implode(' ', [
      !empty($data['onMenu']) ? '' : 'offMenu',
      !empty($data['active']) ? '' : 'nonActive',
      !empty($data['home']) ? 'home' : '',
      !empty($data['module']) ? 'sb-pm-'.$data['module'] : ''
    ])));
    $node['data']['editableContent'] = !empty($data['strName']);
    if (!empty($data['controller'])) {
      $node['data']['dd'] = DdCore::isDdController($data['controller']);
//$node['data']['ddItems'] = DdCore::isItemsController($data['controller']);
//$node['data']['isMaster'] = DdCore::isMasterController($data['controller']);
//if ($node['data']['isMaster']) {
//  $node['data']['slavePageId'] = DbModelCore::get('pages', $data['id'], 'parentId')->r['id'];
//}
    }
    if (!empty($data['module'])) {
      $node['data']['canLinked'] = $data['module'] != 'link';
      $node['data']['path'] = (string)$data['path'];
//PageModuleCore::hasStaticLayout($data['module']);
    }
  }

}
