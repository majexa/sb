<?php

class PmsbDd extends PmsbAuthorItems {

  protected function init() {
    $this->enable = !empty($this->ctrl->page['settings']['ownerMode']) and
      $this->ctrl->page['settings']['ownerMode'] == 'author';
  }

}
