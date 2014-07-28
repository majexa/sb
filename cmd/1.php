<?php

Sflm::setFrontend('js', 'default');
Sflm::clearCache();
Sflm::frontend('js')->addLib('sb/js/Ngn.pb.js');
Sflm::frontend('js')->store();
//(new FieldEPageId(['name' => 'dummy'], new Form([])))->js();
//die2(Sflm::frontend('css')->getPaths());
