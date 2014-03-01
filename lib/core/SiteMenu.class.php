<?php

class SiteMenu {

  static function menu(CtrlCommon $ctlr) {
    if (!$ctlr->userGroup) {
      return Html::baseDomainLinks(StmCore::menu()).<<<JS
<script>
var eBtns = new Element('div', {
  'class': 'editBlock smIcons bordered blue'
}).inject($('menu'), 'top');
Ngn.btn1('Редактировать', 'edit').inject(eBtns).addEvent('click', function(e){
  e.preventDefault();
  new Ngn.Dialog.TreeEdit.Pages();
});
</script>
JS;
    }
    $tags = DdTags::get('blog', 'cat');
    $tags->getSelectCond()->addF('userGroupId', $ctlr->userGroup['id']);
    return Html::subDomainLinks(DdTagsHtml::treeUl($tags->getData(), '`<a href="/posts/t2.cat.`.$id.`"><i></i><span>`.$title.`</span></a><i></i><div class="clear"></div>`'), $ctlr->userGroup['name']);
  }

}
