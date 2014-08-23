.home .ngn-tree-icon { background-image: url(/i/img/icons/home.png); }
.row_module.type_imagedRadio .radio {
width: 75px;
height: 75px;
background-repeat: no-repeat;
background-position: center 7px;
}
.row_module.type_imagedRadio label {
display: block;
padding-top: 58px;
}
<?

foreach (ClassCore::getDescendants('PageModule', 'PageModule') as $v) {
  $module = $v['name'];
  if (!file_exists(CORE_PAGE_MODULES_PATH.'/'.$module.'/sm-page.png')) continue;
  $pageIcon = '/'.CORE_PAGE_MODULES_DIR.'/'.$module.'/sm-page.png';
  $folderIconClosed = '/'.CORE_PAGE_MODULES_DIR.'/'.$module.'/sm-folder-closed.png';
  $folderIconOpened = '/'.CORE_PAGE_MODULES_DIR.'/'.$module.'/sm-folder-opened.png';
  $homeIcon = '/'.CORE_PAGE_MODULES_DIR.'/'.$module.'/sm-home.png';
  $largeIcon = '/'.CORE_PAGE_MODULES_DIR.'/'.$module.'/large.png';
  print "
.sb-pm-$module .ngn-tree-page-icon { background-image: url($pageIcon) !important; }
.sb-pm-$module .ngn-tree-folder-close-icon { background-image: url($folderIconClosed) !important; }
.sb-pm-$module .ngn-tree-folder-open-icon { background-image: url($folderIconOpened) !important; }
.sb-pm-$module.home .ngn-tree-icon { background-image: url($homeIcon) !important; }
.item-pm-$module .title .page i { background: url($pageIcon) !important; }
.item-pm-$module .title .folder i { background: url($folderIconClosed) !important; }
.dialog .row_module .radio.opt_$module { background-image: url($largeIcon); }

";
}