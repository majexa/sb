<?php

class SbRouterManager extends RouterManager {

  protected function getDefaultRouter() {
    return new SbRouter($this->req);
  }

}