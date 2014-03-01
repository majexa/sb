<?php

class PbvugTags extends Pbvug {

  protected function init() {
    $this->pbv->oTags->getCond()->addF('userGroupId', $this->pbv->ctrl->userGroup['id']);
  }

}
