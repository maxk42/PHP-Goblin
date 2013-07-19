<?php

  function showPage($path, $params = array(), $return = FALSE) {
    if(!is_array($params))
      $params = array();
    
    if(($data = @file_get_contents($path)) === FALSE)
      die("Error opening file &quot;" . htmlentities($path) . "&quot;.");
    
    foreach($params as $search => $replace)
      $data = str_replace($search, $replace, $data);
    
    if($return)
      return $data;
    
    die($data);
  }

?>