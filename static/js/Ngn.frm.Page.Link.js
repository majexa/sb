Ngn.frm.Page.Link = new Class({
  Extends: Ngn.frm.Page,

  select: function(node) {
    if (!node.data.canLinked) return false;
    this.eInput.set('value', '/' + node.data.path);
    return true;
  }

});