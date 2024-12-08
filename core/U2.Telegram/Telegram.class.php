<?php

namespace Tg;
use \Tg\Exception as ex;

class Telegram {

  private array  $Pool;
  private Core\Bot $Bot;

  //private $Connect;
  //private $Updates = [];

  // --------------------------------------------------------------------------
  function __construct(){
    $this->Bot = new Core\Bot();
  }

  // --------------------------------------------------------------------------
  function __get($name){
    switch($name){
      case 'Updates'   : return $this->getMyUpdates();
      default : throw new ex\Property($this, $name);
    }
  }

  // --------------------------------------------------------------------------
  function __set($name, $value){
    switch($name){
      default : throw new ex\Property($this, $name);
    }
  }

  // --------------------------------------------------------------------------
  private function getMyUpdates(bool $IsPurge=false): array {
    //$Connect = new Connect( new Config() );

    if( Core\Webhook::isEnabled() ){
      return $this->Bot->getUpdate();
    }
    else{
      return $this->Bot->getUpdates($IsPurge);
    }
  }

  // --------------------------------------------------------------------------
  // --------------------------------------------------------------------------
  public function pool(array $Pool){
    $Updates = $this->Bot->getUpdate();
    foreach( $Updates as $Update ){
      foreach( $Pool as $Action ){
        $Action( $Update );
      }
    }
  }

  // --------------------------------------------------------------------------
  public function sendMessage( \Tg\App\Message $Message ): ?array {
    return $this->Bot->sendMessage( $Message->Data );
  }

  // --------------------------------------------------------------------------
  public function editMessage( \Tg\App\Message $Message ): ?array {
    _log('data', $Message->Data );

    return $this->Bot->editMessageText( $Message->Data );
  }
  
  // --------------------------------------------------------------------------
  public function deleteMessage( Core\Message $Message ): ?array {
    $Params = [
      'chat_id' 	 => $Message->Chat->Id,
      'message_id' => $Message->Id,
    ];
    return $this->Bot->deleteMessage( $Params );
  }



  
  // --------------------------------------------------------------------------
  // -- Keyboard --------------------------------------------------------------
  // --------------------------------------------------------------------------
  // public function createInlineKeyboard( string $Name, array $Keyboard ): array {
  //   $Markup = new Tg\InlineKeyboardMarkup([
  //     'inline_keyboard' => $Keyboard
  //   ]);

  //   $Config = new Config(ROOT.'/keyboard.json');
  //   $Config->setValue($Name, $Markup->Keyboard);

  //   return $Markup->Keyboard;
  // }

  

  // --------------------------------------------------------------------------
  // -- Message ---------------------------------------------------------------
  // --------------------------------------------------------------------------
  // public function sendMessage( array $Params ): ?array {
  //   return $this->Bot->sendMessage( $Params );
  // }

  // --------------------------------------------------------------------------
  // public function deleteMessage(array $Params ): ?array {
  //   return $this->Bot->deleteMessage( $Params );
  // }

  // --------------------------------------------------------------------------
  // public function editMessageText( Core\Message $Message, array $Params ): ?array{
  //   if (
  //     ( !empty($Params['text'])         && ( strip_tags( $Params['text'] ) == $Message->Text  ) ) &&
  //     ( !empty($Params['reply_markup']) && ( $Params['reply_markup']       == $Message->ReplyMarkup->Data ) )
  //   ) return null;

  //   return $this->Bot->editMessageText( [
  //     'chat_id' 	   => $Message->Chat->Id,
  //     'message_id'   => $Message->Id,
  //     'text'         => $Params['text'],
  //     'parse_mode'   => isset($Params['parse_mode']) ? $Params['parse_mode'] : 'HTML',
  //     'reply_markup' => empty($Params['reply_markup']) ? json_encode( $Message->ReplyMarkup->Data ) : json_encode( $Params['reply_markup'] ),
  //   ] );
  // }

}

/*
class Telegram {
  static $CONNECT;

  private Tg\Bot $Bot;
  private Config $Config;

  //private $Connect;
  //private $Updates = [];

  // --------------------------------------------------------------------------
  function __construct(Config $Config){
    $this->Config    = $Config;
    $this->Bot = new Tg\Bot();
    self::$CONNECT = new Connect( $Config );
  }

  // --------------------------------------------------------------------------
  function __get($name){
    switch($name){
      case 'IsWebHook' : return Tg\Webhook::isEnabled();
      case 'Updates'   : return $this->getMyUpdates();
      default : throw new ex\Property($this, $name);
    }
  }

  // --------------------------------------------------------------------------
  function __set($name, $value){
    switch($name){
      default : throw new ex\Property($this, $name);
    }
  }

  // --------------------------------------------------------------------------
  private function getMyUpdates(bool $IsPurge=false): array {
    //$Connect = new Connect( new Config() );

    if($this->IsWebHook){
      $Responce = $this->Bot->getUpdate();
      return [ $Responce ];
    }
    else{
      $Responce = $this->Bot->getUpdates($IsPurge);
      return $Responce;
    }
  }


  // --------------------------------------------------------------------------
  // -- AboutMe ---------------------------------------------------------------
  // --------------------------------------------------------------------------
  public function aboutMe( ): ?array {
    return $this->Bot->sendMessage( $Params );
  }

  
  // --------------------------------------------------------------------------
  // -- Keyboard --------------------------------------------------------------
  // --------------------------------------------------------------------------
  public function createInlineKeyboard( string $Name, array $Keyboard ): array {
    $Markup = new Tg\InlineKeyboardMarkup([
      'inline_keyboard' => $Keyboard
    ]);

    $Config = new Config(ROOT.'/keyboard.json');
    $Config->setValue($Name, $Markup->Keyboard);

    return $Markup->Keyboard;
  }

  // --------------------------------------------------------------------------
  // -- Message ---------------------------------------------------------------
  // --------------------------------------------------------------------------
  public function sendMessage( array $Params ): ?array {
    return $this->Bot->sendMessage( $Params );
  }

  // --------------------------------------------------------------------------
  public function deleteMessage(array $Params ): ?array {
    return $this->Bot->deleteMessage( $Params );
  }

  // --------------------------------------------------------------------------
  public function editMessageText( Tg\Message $Message, array $Params ): ?array{
    if (
      ( !empty($Params['text'])         && ( strip_tags( $Params['text'] ) == $Message->Text  ) ) &&
      ( !empty($Params['reply_markup']) && ( $Params['reply_markup']       == $Message->ReplyMarkup->Data ) )
    ) return null;

    return $this->Bot->editMessageText( [
      'chat_id' 	   => $Message->Chat->Id,
      'message_id'   => $Message->Id,
      'text'         => $Params['text'],
      'parse_mode'   => isset($Params['parse_mode']) ? $Params['parse_mode'] : 'HTML',
      'reply_markup' => empty($Params['reply_markup']) ? json_encode( $Message->ReplyMarkup->Data ) : json_encode( $Params['reply_markup'] ),
    ] );
  }

}
*/
?>
