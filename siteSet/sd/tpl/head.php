<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title><?= $d['pageHeadTitle'] ?></title>
  <? if ($d['page']['home'] and ($v = Config::getVarVar('yandex', 'verification', true))) { ?>
  <meta name="yandex-verification" content="<?= $v ?>"/>
  <? } ?>
  <base href="<?= (new SiteRequest)->getAbsBase() ?>/" />
  <? if (!empty($d['pageMeta']['description'])) { ?>
  <meta name="description" content="<?= $d['pageMeta']['description'] ?>"/>
  <? } ?>
  <? if (!empty($d['pageMeta']['keywords'])) { ?>
  <meta name="keywords" content="<?= $d['pageMeta']['keywords'] ?>"/>
  <? } ?>
  <?= Sflm::flm('css')->getTags() ?>
  <script type="text/javascript" src="/i/js/tiny_mce/tiny_mce.js"></script>
  <?= Sflm::flm('js')->getTags() ?>
  <?
  // Site Module CSS & JS
  if (!empty($d['page']['module'])) {
    print FileCache::func(function() use ($d) {
      return PageModuleCore::sf('site', $d['page']['module']);
    }, $d['page']['module'].'sf', FORCE_STATIC_FILES_CACHE);
  }
  ?>
</head>
