<?php

namespace Tg\Actions;
use \Tg\Exception as ex;

class AboutMe {

  private \Tg\Core\Message $Message;
    
  // --------------------------------------------------------------------------
  function __construct( \Tg\Core\Message $Message ){
    $this->Message = $Message;
  }

  // --------------------------------------------------------------------------
  function __get($name){
    switch($name){
      case 'Text' : return $this->getMyText();
      default : throw new ex\Property( $this, $name);
    }
  }

  // --------------------------------------------------------------------------
  private function getMyText(){
    $User = $this->Message->From;
    $Info = [
      '<b>Информация об аккаунте:</b>',
      "\n\n",
      //'<blockquote expandable>'.$UserInfo['userName']."\n".$UserInfo['firstName'].' '.$UserInfo['lastName'].'</blockquote>'
      '<blockquote expandable>'.$User->UserName."\n".$User->FirstName.' '.$User->LastName.'</blockquote>'
    ];
    return implode( '',$Info );
  }

  // --------------------------------------------------------------------------
  // --------------------------------------------------------------------------
  static function instance( \Tg\Core\Message $Message ){
    return new self( $Message );
  }

  // --------------------------------------------------------------------------
  static function i( \Tg\Core\Message $Message ){
    return self::instance( $Message );
  }

}

// $UserInfo = $Message->From->UserInfo;
//           $User = $Message->From;
//           $Info = [
//             '<b>Информация об аккаунте:</b>',
//             "\n\n",
//             //'<blockquote expandable>'.$UserInfo['userName']."\n".$UserInfo['firstName'].' '.$UserInfo['lastName'].'</blockquote>'
//             '<blockquote expandable>'.$User->userName."\n".$User->firstName.' '.$User->lastName.'</blockquote>'
//           ];
//           $D = [ 'text' => implode('',$Info) ] ;


//           $this->Telegram->sendMessage( array_merge([
//           'chat_id'      => $ChatId,
//           'parse_mode'   => 'HTML',
//         ], $D) ) ;
?>
