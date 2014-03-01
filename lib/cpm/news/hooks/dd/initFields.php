<?php

$n = Arr::getKeyByValue($this->im->form->fields->initFields, 'name', 'datePublish');
$this->im->form->fields->dields[$n]['system'] = false;
//$this->im->form->fields->initFields();
