<?php

Sflm::setFrontend('default');
Sflm::clearCache();
(new FieldEPageId(['name' => 'dummy'], new Form([])))->js();
//die2(Sflm::frontend('css')->getPaths());
