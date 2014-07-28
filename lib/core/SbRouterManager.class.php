<?php

class SbRouterManager extends RouterManager {

  protected function getDefaultRouter() {
    return new SbRouterNew($this->req);
  }

}