<?php

class Permission {

  function allow($action) {
    return Misc::isAdmin();
  }

}

trait PermissionCtrl {

  function permission() {
    return O::get('Permission');
  }

}