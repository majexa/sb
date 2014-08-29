<table class="productsTable">
<tbody>
<? foreach ($d as $v) { ?>
<tr data-cartId="<?= $v['cartId'] ?>" data-pageId="<?= $v['pageId'] ?>">
  <td><a href="<?= $v['image'] ?>" class="thumb lightbox" target="_blank"><img src="<?= $v['sm_image'] ?>" /></a></td>
  <td width="100%">
    <a href="<?= DbModelCore::get('pages', $v['pageId'])['path'].'/'.$v['id'] ?>" target="_blank"><?= $v['title'] ?></a>
    <?= $v['orderParams'] ? '<br /><span class="dgray">'.$this->enumDddd($v['orderParams'], '$title.`: `.$value[`title`]').'</span>' : '' ?>
  </td>
  <td nowrap class="cnt"><span class="cntV"><?= $v['cnt'] ?></span> шт.</td>
  <td nowrap><span class="priceV"><?= $v['price'] ?></span> Ᵽ</td>
  <td class="iconsSet"><a href="#" class="delete tooltip" title="Убрать из корзины"><i></i></a></td>
</tr>
<? } ?>
</tbody>
<tfoot>
<tr class="total">
  <td colspan="4" nowrap>И того: <span class="priceV"><?= $v['price'] ?></span> Ᵽ</td>
  <td></td>
</tr>
</tfoot>
</table>
<a href="#" class="btn" id="cartClean"><span>Очистить корзину</span></a>
<? if (!Auth::get('id')) { ?>
  <a href="#" class="btn btnAuth"><span>Авторизоваться</span></a>
<? } ?>
<script type="text/javascript">
new Ngn.Cart.OrderList(document.getElement('.productsTable'), $('cartClean'));
</script>