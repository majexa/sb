<?php

class PmsbUserGroupHome extends PmsbAbstract {

  function initBlocks() {
    $this->addBlock([
      'colN' => 1,
      'type' => 'userGroupInfo',
      'html' => Tt()->getTpl('pmsb/userGroup', $this->ctrl->userGroup
    )]);
  }
}
