<?php

class PbvCalendar extends PbvAbstract {

  static $cachable = false;

  function _html() {
    if (!DdCore::isItemsController($this->ctrl->page['controller'])) return;
    if ($this->ctrl->action != 'list') return;
    if (!isset($this->ctrl->page['settings']['dateField']) or !$this->ctrl->page['settings']['dateField']) return;
    $calendar = new CalendarItems($this->ctrl->page['path'], $this->ctrl->oManager->items);
    $calendar->items->dateField = $this->ctrl->page['settings']['dateField'];
    if ($this->ctrl->month) {
      $month = $this->ctrl->month;
      $year = $this->ctrl->year;
    } else {
      $month = date('n');
      $year = date('Y');
    }
    $d['calendar'] = $this->getBesideMonths($month, $year);
    $d['calendar']['table'] = $calendar->getMonthView($month, $year);
    if ($this->ctrl->page['settings']['dateField']) {
      $d['months'] = $this->ctrl->oManager->items->getMonths($this->ctrl->page['settings']['dateField']);
    }
    return Tt()->getTpl('common/calendar', $d);
  }

  protected function getBesideMonths($m, $y) {
    $months = Config::getVar('ruMonths2');
    $prevMonthTime = mktime(0, 0, 0, $m - 1, 1, $y);
    $nextMonthTime = mktime(0, 0, 0, $m + 1, 1, $y);
    $r = [];
    $r['prevMonth'] = $months[date('n', $prevMonthTime)];
    $r['nextMonth'] = $months[date('n', $nextMonthTime)];
    $r['prevMonthDate'] = date('m.Y', $prevMonthTime);
    $r['nextMonthDate'] = date('m.Y', $nextMonthTime);
    return $r;
  }


}
