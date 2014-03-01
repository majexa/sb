<?php

class PbvugItems extends Pbvug {

  protected function init() {
    $this->pbv->items->cond->addF('userGroupId', $this->pbv->ctrl->userGroup['id']);
  }

}
