<?php

namespace Tg\App;
use \Tg\Exception as ex;

class Message {
  private  string           $Mode = 'create';  // create, edit
  private \Tg\Core\Message  $Message;
  private ?string           $ParseMode = null; // Markdown, HTML
  private  string           $MessageText;
  private ?array            $InlineKeyboard = null;
    
  // --------------------------------------------------------------------------
  function __construct( \Tg\Core\Message $Message ) {
    $this->Message = $Message;
  }

  // --------------------------------------------------------------------------
  function __get($name) {
    switch($name){
      case 'Data' : return $this->getMyData();
      default : throw new ex\Property( $this, $name );
    }
  }

  // --------------------------------------------------------------------------
  private function getMyData(): array {
    $Params = [
      'chat_id'      => $this->Message->Chat->Id,
      'message_id'   => $this->Mode == 'edit' ? $this->Message->Id : null,
      'text'         => $this->MessageText,
      'parse_mode'   => $this->ParseMode,
      'reply_markup' => $this->Mode == 'edit' ? 
                            ( $this->Message->ReplyMarkup ? json_encode( $this->Message->ReplyMarkup->Data )              : null )
                          : ( $this->InlineKeyboard       ? json_encode( [ 'inline_keyboard' => $this->InlineKeyboard ] ) : null )
    ];

    // if( $this->ParseMode ) $Params = array_merge( $Params, [
    //   'parse_mode' => $this->ParseMode
    // ] );

    // if( $this->InlineKeyboard ) $Params = array_merge( $Params, [
    //   'reply_markup' => json_encode( [ 'inline_keyboard' => $this->InlineKeyboard ] )
    // ] );

    return $Params;
  }

  // --------------------------------------------------------------------------
  // --------------------------------------------------------------------------
  public function setMode( string $Mode ){
    $this->Mode = $Mode;
    return $this;
  }

  // --------------------------------------------------------------------------
  public function parseMode( string $Mode ){
    $this->ParseMode = $Mode;
    return $this;
  }

  // --------------------------------------------------------------------------
  public function text( string $MessageText ) {
    $this->MessageText = $MessageText;
    return $this;
  }

  // --------------------------------------------------------------------------
  public function inlineKeyboard( array $Keyboard ) {
    $this->InlineKeyboard = $Keyboard;
    return $this;
  }

  // --------------------------------------------------------------------------
  // --------------------------------------------------------------------------
  static function instance( \Tg\Core\Message $Message ) {
    return new self( $Message );
  }

  // --------------------------------------------------------------------------
  static function i( \Tg\Core\Message $Message ) {
    return self::instance( $Message );
  }

  // --------------------------------------------------------------------------
  static function create( \Tg\Core\Message $Message ) {
    return self::instance( $Message )->setMode('create');
  }
  
  // --------------------------------------------------------------------------
  static function edit( \Tg\Core\Message $Message ) {
    return self::instance( $Message )->setMode('edit');
  }

}
?>
