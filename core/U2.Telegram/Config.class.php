<?php

namespace Tg;
use \Tg\Exception as ex;

class Config {
  static array $INSTANCE = [];

  private $Config;
  private $ConfigFilePath;

  // --------------------------------------------------------------------------
  function __construct($FilePath=null){
    $this->ConfigFilePath = $FilePath ? $FilePath : TGROOT.'/config.json';
    $this->Config         = $this->get();
  }

  // --------------------------------------------------------------------------
  function __get($name){
    switch($name){
      case 'Description' : return basename($this->ConfigFilePath);      // идентификатор config файла
      // case 'TelegramToken' : return $this->getMyTelegramToken();
      // case 'SecretToken'   : return $this->getMySecretToken();
      // case 'WebhookUrl'    : return $this->getMyWebhookUrl();
      // case 'LastUpdateId'  : return $this->getMyLastUpdateId();
      default : throw new ex\Property($this, $name);
    }
  }

  // --------------------------------------------------------------------------
  function __set($name, $value){
    switch($name){
      //case 'SecretToken' : return $this->setMySecretToken($value);
      default : throw new ex\Property($this, $name);
    }
  }

  // --------------------------------------------------------------------------
  private function get(): array {
    return file_exists($this->ConfigFilePath) ? json_decode( file_get_contents($this->ConfigFilePath), JSON_OBJECT_AS_ARRAY ) : [];
  }

  // --------------------------------------------------------------------------
  private function put(): void {
    file_put_contents( $this->ConfigFilePath, json_encode(
      $this->Config, JSON_PRETTY_PRINT           // форматирование пробелами
                   | JSON_UNESCAPED_SLASHES      // не экранировать '/'
                   | JSON_UNESCAPED_UNICODE      // не кодировать текст
    ));
  }

  // --------------------------------------------------------------------------
  // --------------------------------------------------------------------------
  public function setValue( $name, $value ): \Tg\Config {
    $this->Config[$name] = $value;
    $this->put();
    return $this;
  }

  // --------------------------------------------------------------------------
  public function getValue( $name ) {
    if ( !isset($this->Config[$name]) ) throw new ex\Config($this, $name);
    return $this->Config[ $name ];
  }

  // --------------------------------------------------------------------------
  // --------------------------------------------------------------------------
  static function instance($FilePath=null) {
    $Key = $FilePath;
    if ( isset(self::$INSTANCE[$Key]) ) return self::$INSTANCE[$Key];
    return self::$INSTANCE[$Key] = new self($FilePath);
  }
}
?>
