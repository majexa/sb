/**
 * @requires Ngn.Frm.Page.Id
 */
Ngn.Form.El.PageId = new Class({
  Extends: Ngn.Form.El,

  init: function() {
    new Ngn.Frm.Page.Id(this.eRow, {dd: true});
  }

});