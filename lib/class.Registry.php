<?php

class Registry {
  
  function Registry() {	// constructor
    $args = func_get_args();
    
    foreach($args as $arg) {
      if(!is_array($arg))
        continue;
      
      foreach($arg as $k => $v)
        $this->$k = $v;
    }
    
    return TRUE;
  }
  
  function get($name) {
    return $this->$name;
  }
  
  
  function set($name, $value) {
    return $this->$name = $value;
  }
  
  function has($name) {
    return isset($this->$name);
  }
}

?>