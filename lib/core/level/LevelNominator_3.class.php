<?php

class LevelNominator_3 extends LevelNominator {
  
  /**
   * Сутки
   *
   * @var integer
   */
  protected $interval = 864200;
  
  protected $level = 3;

  protected $condition = 'or';
  
  protected $requirements = [
    'dd' => 20,
    'comments' => 100,
  ];
  
}
