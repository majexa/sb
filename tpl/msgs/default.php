<div class="msgs">
<a name="msgs"></a>

<? $canCreate = ($d['ctrl']->d['priv']['sub_create'] or $d['ctrl']->d['privAuth']['sub_create']) ?>

<!-- Add msg form -->
<? if (1 or Auth::check() or $d['anonym']) { ?>
  <? if ($canCreate) { ?>
  <a name="msgAdd"></a>
  <h2>Комментарии</h2>
  <? if ($d['errors']) $this->tpl('common/errors', $d['errors']); ?>
  <div id="msgAdd">
  <form action="<?= $this->getPath() ?>" method="POST" id="msgForm">
    <input type="hidden" name="action" value="sub_create" />
    <input type="hidden" name="sub" value="msgs" />
    <input type="hidden" name="ansId" id="ansId" />
    <!-- Begin FSSB tags -->
    <?= $d['fsbbTags'] ?>
    <!-- End FSSB tags -->
    <? if ($d['anonym']) { ?>
      <p>���� ���/���: <input type="text" name="nick" id="msgNick" class="fldLarge" /></p>
    <? } ?>
    <div class="iconsSet bordered">
      <? foreach (CommentsTags::getToolbarTags($d['level']) as $tag => $v) { ?>
        <a href="#" class="<?= $v['class'] ?>" onclick="TSel.insTag('<?= $tag ?>'); return false;" title="<?= $v['title'] ?>"><i></i></a>
      <? } ?>
      <!-- <a href="<?= $this->getPath() ?>?a=sub_iiUpload" class="file" onclick="openwin(this.href, 280, 110); return false;" title="��������� �������� ��� �����"><i></i></a> -->
      <div class="clear"><!-- --></div>
    </div>
    <div class="msgTextHolder">
      <textarea name="text" id="msgText" class="msgText"><?= $_POST['text'] ?></textarea>
    </div>
    <div class="clear"><!-- --></div>
    <div class="btnSubmitBlock">
    <a href="#" class="btn btnSubmit btnSubmitLarge" id="btnSubmit" title="(Ctrl+Enter)"><span><span>���������</span></span></a>
    <div id="answerBlock"></div>
    </div>
    <div class="clear"><!-- --></div>
  </form>
  </div>
  <? } else { ?>
  <div class="info">
    Вы не можете добавлять комментарии в этом разделе.
  </div>
  <? } ?>
<? } else { ?>
  <div class="info">
    Вы не можете добавлять комментарии.
    <a href="/auth">Авторизуйтесь</a> пожалуйста.
  </div>
<? } ?>
<!-- End of add msg form -->

<? if ($d["pNums"]) { ?>
<div class="pNums pNumsTop">
  <?= $d["pNums"] ?>
  <div class="clear"><!-- --></div>
</div>
<? } ?>
<!-- Messages -->
<div class="items itemsList markedList" id="_msgs">
<? if ($d['items']) { ?>
  <?
  foreach ($d['items'] as $k => $v) {
    $newMsgClass = '';
    if (is_array($d['viewedMsgsIds']))
      $v['newMsgClass'] = in_array($v['id'], $d['viewedMsgsIds']) ? '' : ' new1';
    else $v['newMsgClass'] = '';
    if ($d['ctrl']->priv['sub_edit']) $v['canEdit'] = true;
    if ($d['ctrl']->priv['sub_create']) $v['canCreate'] = true;
    $this->tpl('common/msg', $v);
  }
  ?>
<!-- End of messages -->
<? } else { ?>
  <p id="youLlBeFirst">Вы будете первым</p>
<? } ?>
<!-- close id="_msgs" -->
</div>
<? if ($d["pNums"]) { ?>
  <div class="pNums pNumsBottom">
    <?= $d["pNums"] ?>
    <div class="clear"><!-- --></div>
  </div>
<? } ?>
</div>

<?/*
<? if ($canCreate) { ?>  
  <script type="text/javascript">
  new Ngn.msgs.MsgsLayout('<?= $this->getPath() ?>', '<?= $this->getUserPath(311) ?>', {
    authorized: <?= Arr::jsValue(Auth::check()) ?>
  });
  </script>
<? } ?>

<script type="text/javascript">
var initPNumsAnchors = function() { 
  document.getElement('.msgs').getElements('div[class~=pNums]').each(function(ePNums) {
    ePNums.getElements('a').each(function(eA) {
      eA.set('href', eA.get('href') + '#bmt_comments');
    });
  });
};
window.addEvent('domready', function() {
  initPNumsAnchors();
});

var TSel = new TextareaSelection($('msgText'));
</script>
*/?>
