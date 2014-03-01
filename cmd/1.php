<?php

Sflm::$frontend = 'default';
Sflm::clearCache();
(new FieldEPageId(['name' => 'dummy'], new Form([])))->js();
//die2(Sflm::flm('css')->getPaths());
