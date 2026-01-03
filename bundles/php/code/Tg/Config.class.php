<?php

namespace Tg;
use \Tg\Exception as ex;

class Config {
  static array $INSTANCE = [];
  private $Config;
  private $FilePath;

  // --------------------------------------------------------------------------
  function __construct( string $filePath = null ) {
    $this->Config = $this->get( $filePath );
  }

  // --------------------------------------------------------------------------
  function __get( $name ) {
    switch( $name ) {
      // case 'Description' : return basename( $this->FilePath );      // идентификатор config файла
      // case 'TelegramToken' : return $this->getMyTelegramToken();
      // case 'SecretToken'   : return $this->getMySecretToken();
      // case 'WebhookUrl'    : return $this->getMyWebhookUrl();
      // case 'LastUpdateId'  : return $this->getMyLastUpdateId();
      default : throw new ex\Property( $this, $name );
    }
  }

  // --------------------------------------------------------------------------
  function __set( $name, $value ) {
    switch( $name ){
      //case 'SecretToken' : return $this->setMySecretToken($value);
      default : throw new ex\Property( $this, $name );
    }
  }

  // --------------------------------------------------------------------------
  private function get( string $filePath = null ): array {
    $this->FilePath = file_exists( $filePath ) ? $this->FilePath : (
      file_exists( TGTOP.'/config.json' ) ? TGTOP.'/config.json' : (
        file_exists( TGUPPER.'/config.json' ) ? TGUPPER.'/config.json' : null
      )
    );

    if ( ! $this->FilePath ) throw new ex( 'Error: config file not found' );
    return json_decode( file_get_contents( $this->FilePath ), JSON_OBJECT_AS_ARRAY );
  }

  // --------------------------------------------------------------------------
  private function put(): self {
    file_put_contents( $this->FilePath, json_encode( $this->Config, JSON_PRETTY_PRINT           // форматирование пробелами
                                                                  | JSON_UNESCAPED_SLASHES      // не экранировать '/'
                                                                  | JSON_UNESCAPED_UNICODE ));  // не кодировать текст
    return $this;
  }

  // --------------------------------------------------------------------------
  // --------------------------------------------------------------------------
  public function isEnable( $name ): bool {
    return isset( $this->Config[ $name ] );
  }

  // --------------------------------------------------------------------------
  public function setValue( $name, $value ): self {
    $this->Config[ $name ] = $value;
    return $this->put();
  }

  // --------------------------------------------------------------------------
  public function getValue( $name ): string {
    if ( !isset($this->Config[ $name ]) ) throw new ex\Config( $this, $name );
    return $this->Config[ $name ];
  }

  // --------------------------------------------------------------------------
  public function delValue( $name ): self {
    unset( $this->Config[ $name ] );
    return $this->put();
  }
  
  // --------------------------------------------------------------------------
  // --------------------------------------------------------------------------
  static function instance( $FilePath=null ) {
    $Key = $FilePath;
    if ( isset( self::$INSTANCE[ $Key ] )) return self::$INSTANCE[ $Key ];
    return self::$INSTANCE[ $Key ] = new self( $FilePath );
  }
}
?>
