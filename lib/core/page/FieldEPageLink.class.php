<?php

class FieldEPageLink extends FieldEText {

  function _js() {
    return "
$('{$this->form->id()}').getElements('.type_pageLink').each(function(el){
  new Ngn.frm.Page.Link(el);
});
";
  }

}