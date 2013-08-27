<?php

spl_autoload_register(function($class) {
        if(!strstr($class, 'Jade'))
            return;

        $explode = explode('\\', $class);
        $dir = 'vendor/jade.php/src/'.join(DIRECTORY_SEPARATOR, array_slice($explode, 0, count($explode)-1));
        $filename = array_slice($explode, -1)[0];
        $f = Kohana::find_file($dir, $filename, 'php');

        include_once($f);
    });

class Kohana_View_Jade {

  var $jade;
  var $template = NULL;
  var $path = 'views/';
  var $cache_path = 'jade/';
  var $vars = array();

  public function __construct($template=NULL)
  {
    $this->before();
    if($template)
    {
      $this->template = $template;
    }
  }

  function jade_php($jade, $vars)
  {
    foreach($vars as $k => $v){
      ${$k} = $v;
    }
    require_once($jade);
  }

  function render($template=NULL)
  {
    $this->before_render();

    if($template == NULL)
    {
      $template = $this->template;
    }

    $fn = APPPATH.$this->path.$template.'.jade';
    $cache_fn = APPPATH.'cache/'.$this->cache_path.$template.'.php';

    if(!file_exists($cache_fn) 
        OR time() > filemtime($cache_fn) + 5) // 5 sec
    {
      $jade = new Jade\Jade(true);
      file_put_contents($cache_fn, $jade->render($fn));
    }

    ob_start(function(){ });
    $err = $this->jade_php($cache_fn, $this->vars);
    
    $output = ob_get_contents();
    ob_end_flush();


    return $output;
  }

  function before() {}
  function before_render() {}

  function __set($key, $value)
  {
    $this->vars[$key] = $value;
  }

  function __get($key)
  {
    return $this->vars[$key];
  }
}
