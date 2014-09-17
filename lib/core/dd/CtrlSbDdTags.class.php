<?php

class CtrlSbDdTags extends CtrlSbAdmin {

  protected function groupId() {
    return $this->req->param(2);
  }

  function action_json_getTree() {
    Sflm::frontend('css')->addPath('i/css/common/tree.css');
    $this->json['tree'] = (new ClientTree(DdTags::getByGroupId($this->groupId())))->getTree();
  }

  protected function getGrid() {
    $group = DdTagsGroup::getById($this->groupId());
    return Items::grid([
      'head' => ['ID', 'Тэг','Кол-во записей'],
      'body' => array_map(function($v) {
        return [
          'id' => $v['id'],
          'tools' => [
            'delete' => 'Удалить',
            'edit' => [
              'type' => 'inlineTextEdit',
              'action' => 'ajax_rename',
              'paramName' => 'title',
              'elN' => 1
            ]
          ],
          'data' => Arr::filterByKeys($v, ['id', 'title', 'cnt'])
        ];
      }, (new DdTagsTagsFlat($group))->getTags())
    ]);
  }

  function action_json_getItems() {
    $this->json = $this->getGrid();
    $this->json['title'] = 'Тэги поля «'.DdTagsGroup::getById($this->groupId())->title.'»';
  }

}