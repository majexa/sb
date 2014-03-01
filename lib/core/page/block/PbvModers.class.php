<?php

class PbvModers extends PbvAbstract {

  function _html() {
    return Tt()->getTpl(
      'pageBlocks/moders',
      O::get('Privileges')->getUsers($this->pageBlock['settings']['pageId'], 'edit')
    );
  }

}
