<?php

class Kohana_View_Jade_Layout extends Kohana_View_Jade {

  var $content = NULL;
  var $sub_template = NULL;
  var $current_page = NULL;

  public function __construct($template)
  {
    $this->content = new View_Jade($template);

    parent::__construct('layout');
  }

  public function render($template=NULL)
  {
    $this->vars['content'] = $this->content->render();
    return parent::render();
  }
}
