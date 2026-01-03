<?php

namespace Tg\Core;
use \Tg\Exception as ex;

/**
 * Class \Tg\Core\InlineKeyboardMarkup
 * 
 * @url https://core.telegram.org/bots/api#inlinekeyboardmarkup
 * 
 * @properties
 * - inline_keyboard  Array of Array of InlineKeyboardButton  Array of button rows, each represented by an Array of InlineKeyboardButton objects
 */

class InlineKeyboardMarkup {
  public array $InlineKeyboard;   // Array of Array   Array of button rows, each represented by an Array of InlineKeyboardButton objects
    
  // --------------------------------------------------------------------------
  function __construct( array $Markup ) {
    $this->InlineKeyboard = $Markup[ 'inline_keyboard' ];

    foreach ($this->InlineKeyboard as $X=>$Row){
      foreach ($Row as $Y=>$Button){
        $this->InlineKeyboard[ $X ][ $Y ] = new InlineKeyboardButton( $Button );
      }
    }
  }

  // --------------------------------------------------------------------------
  function __get( $Name ) {
    switch ( $Name ) {
      case 'Keyboard' : return $this->getMyKeyboard();
      case 'Data'     : return json_encode( [ 'inline_keyboard' => $this->getMyKeyboard() ], JSON_UNESCAPED_SLASHES      // не экранировать '/'
                                                                                           | JSON_UNESCAPED_UNICODE );   // не кодировать текст
      default : throw new ex\Property( $this, $Name );
    }
  }

  // --------------------------------------------------------------------------
  // --------------------------------------------------------------------------
  private function getMyKeyboard(): array {
    $Keyboard = $this->InlineKeyboard;

    foreach ( $Keyboard as $x=>$row ) {
      foreach ( $row as $y=>$button ){
        $Keyboard[ $x ][ $y]  = $button->Data;
      }
    }
    return $Keyboard;
  }

  // --------------------------------------------------------------------------
  // --------------------------------------------------------------------------
  public function getButtonText( string $CallbackData ): ?string {
    $Text = null;

    foreach ($this->InlineKeyboard as $X=>$Row){
      foreach ($Row as $Y=>$Button){
        $Tmp = $Button->getButtonText( $CallbackData );
        if( $Tmp ) $Text = $Tmp;
      }
    }
    return $Text;
  }
}
?>
