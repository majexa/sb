/**
 * @requires Ngn.frm.Page.Id
 */
Ngn.Form.El.PageId = new Class({
  Extends: Ngn.Form.El,

  init: function() {
    new Ngn.frm.Page.Id(this.eRow, {dd: true});
  }

});