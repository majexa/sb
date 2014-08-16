<?= $d['body'] ?>

<? //prr($d['page']) ?>

<script>
  Ngn.toObj('Ngn.sb');
  Ngn.sb.page = <?= Arr::jsObj($d['page']->r) ?>;
  Ngn.isAdmin = <?= Misc::isAdmin() ? 'true' : 'false' ?>;
  window.addEvent('domready', function() {
    Ngn.addBtnAction('.topBtns .new', function() {
      new Ngn.Dialog.RequestForm({
        id: 'store',
        title: 'Добавление записи',
        url: '/store/json_new',
        width: 300,
        onSubmitSuccess: function() {
          window.location.reload(true);
        }
      });
    });
    Ngn.addBtnAction('.authBar .btnSubmit', function() {
      $('authForm').submit();
    });
  });
</script>
