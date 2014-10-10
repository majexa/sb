<div class="authBar">
  <? if (!Auth::get('id')) { ?>
    <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="post" id="authForm">
      <div class="item"><input type="text" class="fld" name="authLogin" id="authLogin" placeholder="Login"/></div>
      <div class="item"><input type="password" class="fld" name="authPass" id="authPass" placeholder="Password"/></div>
      <div class="item" style="position:relative">
        <a href="#" class="btn btnSubmit" id="btnLogin"><span>Войти</span></a>
        <? if ($d['errors']) $this->tpl('slideTips/auth', $d['errors']); ?>
      </div>
      <div class="item">
        <a href="/userReg" class="btnFake userReg pseudoLink"><span>Регистрация</span></a>
      </div>
    </form>
  <?
  }
  else {
    ?>
    <div id="personal" class="iconsSet">
      <div class="item myLogin"><a class="pseudoLink"><i></i><b><?= UsersCore::getTitle(Auth::get('id')) ?></b></a></div>
      <div class="item gray"><a href="<?= $d['path'] ?>?logout=1"><?= Lang::get('logout') ?></a></div>
    </div>
  <? } ?>
  <? if ($d['topItems']) foreach ($d['topItems'] as $v) { ?>
    <div class="item"><div class="btnFake"><span><?= $v ?></span></div></div>
  <? } ?>
</div>
