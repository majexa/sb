<?php

class PbvugLastComments extends Pbvug {

  protected function init() {
    $this->pbv->comments->cond->addF('comments_srt.userGroupId', $this->pbv->ctrl->userGroup['id']);
  }

}
