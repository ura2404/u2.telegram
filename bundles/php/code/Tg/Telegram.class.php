<?php

namespace Tg;
use \Tg\Exception as ex;

class Telegram {
  private Core\Bot $Bot;


  // --------------------------------------------------------------------------
  function __construct(){
    $this->Bot = new Core\Bot();
  }

  // --------------------------------------------------------------------------
  public function pool(array $Pool){
    $Updates = $this->Bot->getUpdate();
    foreach( $Updates as $Update ){
      foreach( $Pool as $Action ){
        $Action( $Update );
      }
    }
  }
}
?>