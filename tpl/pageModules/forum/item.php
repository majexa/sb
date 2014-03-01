<table class="valign topic">
<tr>
  <td><?= UsersCore::avatar($d['item']['authorId'], $d['item']['authorLogin']) ?></td>
  <td class="author"><div class="t_author"><?= $this->getUserTag($d['item']['authorId'], $d['item']['authorLogin']) ?></div></td>
  <td class="text"><div class="wr"><div class="bubbles"></div><div class="cont" id="topicText"><?= $d['item']['text'] ?></div></div></td>
</tr>
</table>
<script>
$('pageTitle').inject($('topicText'), 'top');
</script>

<?

$this->tpl('dd/beforeComments', $d, true);

if ($d['showCommentsAfterItem'] and isset($d['oController']->subControllers['comments'])) {
  $this->tpl(
    $d['oController']->subControllers['comments']->d['tpl'],
    $d['oController']->subControllers['comments']->d
  );
}

?>