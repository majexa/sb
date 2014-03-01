<?php

$pageId = 18;
$page = DbModelCore::get('pages', $pageId);
$fields = new DdFields($page['strName']);
$im = new DdItemsManagerPage(new DdItemsPage($page), new DdFormPage($fields, $page['id']));
foreach ($fields->getFields() as $f) $data[$f['name']] = file_get_contents(SB_PATH.'/ddSampleValues/'.$f['type']);
for ($i=0; $i<10; $i++) $im->create($data);