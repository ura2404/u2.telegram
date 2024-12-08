<?php

namespace Tg;
use \Tg\Exception as ex;

class Connect {
  static $INSTANCE;
  private $Config;
  private $BaseUrl;

  // --------------------------------------------------------------------------
  function __construct(Config $Config=null){
    $this->Config = $Config ? $Config : Config::instance( TGROOT.'/config.json' );
    $this->BaseUrl = 'https://api.telegram.org/bot' . $this->Config->getValue('telegramToken') . '/';
  }

  // --------------------------------------------------------------------------
  function __get($name){
    switch($name){
      default : throw new ex\Property($this, $name);
    }
  }

  // --------------------------------------------------------------------------
  // --------------------------------------------------------------------------
  public function post($Method, array $Params=null): array {
    $Url = $this->BaseUrl. $Method. ( empty($Params) ? null : '?' . http_build_query($Params) );
    $Responce = json_decode( file_get_contents($Url), JSON_OBJECT_AS_ARRAY );
    return $Responce;
  }

  // --------------------------------------------------------------------------
  // --------------------------------------------------------------------------
  static function instance(Config $Config=null){
    if ( isset(self::$INSTANCE) ) return self::$INSTANCE;
    return self::$INSTANCE = new self($Config);
  }

  // --------------------------------------------------------------------------
  static function i(Config $Config=null){
    return self::instance($Config);
  }
  
}
?>
