<?php

class PmsbAuthorItems extends PmsbAbstract {

  function initBlocks() {
    $this->addUserGroupInfoBlock();
    $this->addUserInfoBlock();
    $this->addUserTagsBlock();
  }
  
  protected function addUserGroupInfoBlock() {
    if (!$this->ctrl->userGroup or $this->ctrl->page->getS('ownerMode') != 'userGroup') return;
    $this->addBlock([
      'colN' => 1,
      'type' => 'userGroupInfo',
      'html' => Tt()->getTpl('pmsb/userGroup', $this->ctrl->userGroup),
    ]);
  }

  protected function addUserBlock(DbModelUsers $user) {
    $this->addBlock([
      'colN' => 1,
      'type' => 'userInfo',
      'html' => Tt()->getTpl('pmsb/userInfo', [
        'title' => UsersCore::name($user),
        'user' => $user,
        'profile' => UsersCore::profile($user['id'])
      ])
    ]);
  }
  
  protected function addUserInfoBlock() {
    if (isset($this->ctrl->d['itemUser'])) $this->u = $this->ctrl->d['itemUser'];
    elseif (isset($this->ctrl->d['itemsUser'])) $this->u = $this->ctrl->d['itemsUser'];
    if (!isset($this->u)) return;
    $this->addUserBlock($this->u);
  }
  
  protected function addUserTagsBlock() {
    if ($this->ctrl->action != 'list') return;
    if (!isset($this->u) or empty($this->ctrl->page['settings']['userTagField'])) return;
    $tagsSelected = Arr::get($this->ctrl->d['tagsSelected'], 'id');
    $ids = db()->selectCol('SELECT id FROM '.DdCore::table($this->ctrl->page['strName']).' WHERE userId=?d', $this->u['id']);
    if ($ids) {
      $d['tags'] = DdTags::items($this->ctrl->page['strName'], $this->ctrl->page['settings']['userTagField'])->getTagsByItemIds($ids);
      foreach ($d['tags'] as $k => $v) {
        $d['tags'][$k]['link'] = DdTags::getLink(
          Tt()->getPath(1).'/u.'.$this->u['id'],
          $v
        );
        $d['tags'][$k]['selected'] = in_array($v['id'], $tagsSelected);
      }
      $d['field'] = O::get('DdFieldItems', $this->ctrl->page['strName'])->
        getItemByField('name', $this->ctrl->page['settings']['userTagField']);
    }
    $this->addBlock([
      'colN' => 3,
      'class' => 'pbSubMenu',
      'type' => 'userItemsTags',
      'html' => Tt()->getTpl('pmsb/userTags', $d)
    ]);
  }
  
  function _processDynamicBlockModels(array &$blockModels) {
    $blockModels = Arr::dropBySubKeys($blockModels, 'colN', 1);
  }

}