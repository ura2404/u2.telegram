  <?php
  require_once __DIR__.'/../core/common.php';

  $M = 0;

  if ( !\Tg\Core\Webhook::check() ) die ('Fuck off !!!');

  $TestKeyboard = [
    [
      [ 'text' => 1, 'callback_data' => 'ak=1' ],
      [ 'text' => 2, 'callback_data' => 'ak=2' ],
      [ 'text' => 3, 'callback_data' => 'ak=3' ]
    ],
    [
      [ 'text' => 4, 'callback_data' => 'ak=4' ],
      [ 'text' => 5, 'callback_data' => 'ak=5' ]
    ],
    [
      [ 'text' => '<<<', 'callback_data' => '/prev' ],
      [ 'text' => '>>>', 'callback_data' => '/next' ]
    ],
    [
      [ 'text' => 'Назад', 'callback_data' => '/back' ]
    ]
  ];
  
  if( $M == 0  ){
    $Telegram = new Tg\Telegram();
    $Telegram->pool([
        function( Tg\Core\Update $Update ) use( $Telegram, $TestKeyboard ) {
          // $Telegram->sendTextMessage($Update->Message, 'Полученный текст: '.$Update->Message->Text);

          $Message = $Update->getMessage();
          $Data    = $Update->getData();
          if ($Update->isMessage)       { _log('update1',$Update); _log('message1',$Message); }
          if ($Update->isCallbackQuery) { _log('update2',$Update); _log('message2',$Message); }

          if ($Update->isMessage) switch ( $Message->Text ) {
            case '/start'   : $Telegram->sendMessage( \Tg\App\Message::create( $Message )->text( 'Команда '.$Update->Message->Text ) ); break;
            case '/about'   : $Telegram->sendMessage( \Tg\App\Message::create( $Message )->text( \Tg\Actions\AboutMe::i( $Message )->Text )->parseMode('HTML') ); break;
            case '/devices' : $Telegram->sendMessage( \Tg\App\Message::create( $Message )->text( 'Устройства' ) ); break;
            case '/test'    : if ( $Update->isMessage) {
                                $Telegram->sendMessage( \Tg\App\Message::create( $Message )->parseMode('HTML')->text( Tg\Actions\AboutMe::i( $Message )->Text )->inlineKeyboard( $TestKeyboard ) );
                              }
                              if ( $Update->isCallbackQuery ) {
                                //$Telegram->sendMessage( \Tg\App\Message::i( $Message )->text( 'Тест' )->inlineKeyboard( $TestKeyboard ) );
                              }
                              break;           
            default: $Telegram->deleteMessage( $Message );
          }

          if ($Update->isCallbackQuery) switch ( $Data ) {
            case 'ak=1'  : $Telegram->editMessage( \Tg\App\Message::edit( $Message )->text('AK=1') ); break;
            case 'ak=2'  : $Telegram->editMessage( \Tg\App\Message::edit( $Message )->text('AK=2') ); break;
            case 'ak=3'  : $Telegram->editMessage( \Tg\App\Message::edit( $Message )->parseMode('HTML')->text( '<a href="http://www.example.com/">inline URL</a>' ) ); break;
            case 'ak=4'  : $Telegram->editMessage( \Tg\App\Message::edit( $Message )->parseMode('HTML')->text( '<a href="https://pp.userapi.com/c841220/v841220591/667c8/16VMgxEjb94.jpg" >Любое название</a>' ) ); break;
            case 'ak=5'  : $Telegram->editMessage( \Tg\App\Message::edit( $Message )->parseMode('HTML')->text( '<a href="https://www.youtube.com/live/DrhVRLO65pk?si=v6-NU1vxX3doxGAi" >Ролик</a>' ) ); break;             
            case '/back' : $Telegram->editMessage( \Tg\App\Message::edit( $Message )->parseMode('HTML')->text( Tg\Actions\AboutMe::i( $Message )->Text ) ); break;
            default: $Telegram->deleteMessage( $Message );
          }
        }
      ]);

    // $App = ( new App\Application($Telegram) )
    //   ->registryAction( '/start', null, new App\Message([ 'text' => '$aboutMe()' ]) )
    //   ->registryAction( '/about', null, '$aboutMe()' )
    //   ->registryAction( '/list' , null, '$aboutMe()' )
    //   ->go();
  }


  // $Config   = new U2\Config( ROOT.'/config.json' );
  // $Telegram = new U2\Telegram( $Config );



  // ============================================================================================  
  if( $M == 1 ){
    $Config   = new Tg\Config( ROOT.'/config.json' );
    $Telegram = new Tg\Telegram( $Config );

    if ( !$Telegram->checkWebHookSecret() ) die ('Fuck off !!!');
    
    $Keyboard1 = [
      [
        [ 'text' => '1', 'callback_data' => 'k=1' ],
        [ 'text' => '2', 'callback_data' => 'k=2' ],
        [ 'text' => '3', 'callback_data' => 'k=3' ],
      ],
      [
        [ 'text' => '4', 'callback_data' => 'k=4' ],
        [ 'text' => '5', 'callback_data' => 'k=5' ],
        [ 'text' => '6', 'callback_data' => 'k=6' ],
      ],
      [
        [ 'text' => '7', 'callback_data' => 'k=7' ],
        [ 'text' => '8', 'callback_data' => 'k=8' ],
        [ 'text' => '9', 'callback_data' => 'k=9' ],
      ]
    ];

    $Keyboard2 = [
      [
        [ 'text' => '1', 'callback_data' => 'ak=1' ],
        [ 'text' => '2', 'callback_data' => 'ak=2' ],
        [ 'text' => '3', 'callback_data' => 'ak=3' ],
      ],
      [
        [ 'text' => 'Назад', 'callback_data' => '/back' ],
      ],
    ];

    $Telegram->createInlineKeyboard('keyboard1', $Keyboard1);
    $Telegram->createInlineKeyboard('keyboard2', $Keyboard2);

    _log( 'updates', $Telegram->getUpdates() );
    //exit;


    // try{
      foreach ($Telegram->getUpdates() as $Update){
        $Message = $Update->getMessage();
        if(!$Message) continue;

        $ChatId    = $Update->getMessage()->Chat->Id;
        $Text      = $Update->getMessage()->Text;
        $MessageId = $Update->getMessage()->Id;
        $Data      = $Update->isCallbackQuery ? $Update->CallbackQuery->Data : 'no data';

        _log( 'update'             , $Update );
        // _log( ROOT.'/log/update_message'     , $Update->getMessage() );
        _log( 'update_data'        , $Update->isCallbackQuery ? $Update->CallbackQuery->Data : 'no data');
        // _log( ROOT.'/log/update_char_id'     , $ChatId );
        // _log( ROOT.'/log/update_text'        , $Text );
        // _log( ROOT.'/log/update_message_id'  , $MessageId );
        // _log( ROOT.'/log/update_message_type', $Update->isMessage ? 'message' : 'callbackquery' );

        if($Update->isMessage){
          _log( 'update_response', $Telegram->sendMessage( [
            'chat_id'      => $ChatId,
          //'text'         => date('Y-m-d H:i') . ' <b>' . $Text .'</b>',
            'text'         => $Text,
            'parse_mode'   => 'HTML',
            'reply_markup' => json_encode( [ 'inline_keyboard' => (new \Tg\Keyboard('keyboard1'))->Data ] )
          ] ));    
        }

        if($Update->isCallbackQuery){
          if($Data == 'k=9') $KB = new \Tg\Keyboard('keyboard2');
          else $KB = new \Tg\Keyboard('keyboard1');


          // _log( ROOT.'/log/update_response', $Telegram->editMessageText( [
          //   'chat_id' 	   => $ChatId,
          //   'message_id'   => $MessageId,
          //   'text'         => '<blockquote expandable>Expandable block quotation started\nExpandable block quotation continued\nExpandable block quotation continued\nHidden by default part of the block quotation started\nExpandable block quotation continued\nThe last line of the block quotation</blockquote>',
          //   'parse_mode'   => 'HTML',
          //   'reply_markup' => json_encode( [ 'inline_keyboard' => $KB->Data ] ),
      
          // ] ));    

          _log( 'update_response', $Telegram->editMessageText( $Message, [
            'text'         => '<blockquote expandable>Expandable block quotation started\nExpandable block quotation continued\nExpandable block quotation continued\nHidden by default part of the block quotation started\nExpandable block quotation continued\nThe last line of the block quotation</blockquote>',
            'reply_markup' => [ 'inline_keyboard' => $KB->Data ],
          ] ));

        }
      }
//      '_text'         => $Data. '<b><i> 100</i></b><span class="tg-spoiler">qaz</span>',

    // }
    // catch( Throwable $e ){
    //   echo $e->getMessage();
    // }
  }

  // ============================================================================================
  if( $M == 2 ){
    $Ts = date('Y-m-d H:i');

    $Config = json_decode( file_get_contents(ROOT.'/config.json'), JSON_OBJECT_AS_ARRAY );
    $BaseUrl = 'https://api.telegram.org/bot' . $Config['telegramToken'] . '/';

    // --------------------------------------------------------------------------
    $_sendMessage = function($ChatId, $MessageText, $ReplyMarkup) use($BaseUrl){
      // $Reply_markup = json_encode([
      //   //'keyboard' => [$Btn],
      //   //'resize_keyboard' => true,
      //   'inline_keyboard' => $BtnI,
      // ]);
    
      $Method = 'sendMessage';
      $Params = [
        'chat_id' 	   => $ChatId,
        'text'         => $MessageText,
        'reply_markup' => json_encode($ReplyMarkup)
      ];
    
      $Url = $BaseUrl . $Method. '?' . http_build_query($Params);
      file_get_contents($Url);
      //_log( 'responce2', json_decode(file_get_contents($Url),JSON_OBJECT_AS_ARRAY) );
    };

    // --------------------------------------------------------------------------
    $_deleteMessage = function($ChatId, $MessageId) use($BaseUrl){
      $Method = 'deleteMessage';
      $Params = [
        'chat_id' 	 => $ChatId,
        'message_id' => $MessageId,
      ];

      $Url = $BaseUrl . $Method. '?' . http_build_query($Params);
      file_get_contents($Url);
      //_log( 'responce2', json_decode(file_get_contents($Url),JSON_OBJECT_AS_ARRAY) );
    };

    // --------------------------------------------------------------------------
    $_editMessage = function($ChatId, $MessageId, $MessageText, $ReplyMarkup) use($BaseUrl){
      $Method = 'editMessageText';
      $Params = [
        'chat_id' 	   => $ChatId,
        'message_id'   => $MessageId,
        'text'         => $MessageText,
        'reply_markup' => json_encode($ReplyMarkup),
      ];

      $Url = $BaseUrl . $Method. '?' . http_build_query($Params);
      file_get_contents($Url);
      //_log( 'responce2', json_decode(file_get_contents($Url),JSON_OBJECT_AS_ARRAY) );
    };

    // --------------------------------------------------------------------------
    // --------------------------------------------------------------------------
    // --------------------------------------------------------------------------
    $Responce = file_get_contents('php://input');
    $Update = json_decode( $Responce, JSON_OBJECT_AS_ARRAY );

    //_log( 'update2', $Ts . PHP_EOL . print_r($Update,1) );

    $Message  = isset($Update['message'])        ? $Update['message']       : null;
    $Callback = isset($Update['callback_query']) ? $Update['callback_query'] : null;

    // --- inline menu -----------------------
    $BtnI = [
      [
        [ 'text' => '1', 'callback_data' => 'k=1' ],
        [ 'text' => '2', 'callback_data' => 'k=2' ],
        [ 'text' => '3', 'callback_data' => 'k=3' ],
      ],
      [
        [ 'text' => '4', 'callback_data' => 'k=4' ],
        [ 'text' => '5', 'callback_data' => 'k=5' ],
        [ 'text' => '6', 'callback_data' => 'k=6' ],
      ],
      [
        [ 'text' => '7', 'callback_data' => 'k=7' ],
        [ 'text' => '8', 'callback_data' => 'k=8' ],
        [ 'text' => '9', 'callback_data' => 'k=9' ],
      ],
      [
        [ 'text' => '0', 'callback_data' => 'k=0' ],
      ],
      [
        [ 'text' => 'Назад', 'callback_data' => '/back' ],
      ],
    ];

    // -------------------------------------------
    if($Message){
      $MessageText = $Message['text'];
      $ChatId      = $Message['chat']['id'];
      $ReplyMarkup = [
        //'keyboard' => [$Btn],
        //'resize_keyboard' => true,
        'inline_keyboard' => $BtnI,
      ];
    }

    if($Callback){
      //$MessageText = $Callback['message']['text'];
      $MessageText = $Callback['data'];
      $MessageId   = $Callback['message']['message_id'];
      $ChatId      = $Callback['message']['chat']['id'];
      $ReplyMarkup = $Callback['message']['reply_markup'];
    }

    /*
    // --- menu -----------------------
    $Btn[] = [ 'text' => 'Информация обо мне'     , 'callback_data' => '/whoami'   ];
    $Btn[] = [ 'text' => 'Список устройств'       , 'callback_data' => '/list'     ];
    $Btn[] = [ 'text' => 'Регистрпация устройства', 'callback_data' => '/registry' ];

    // $Reply_markup = json_encode([
    //   'keyboard' => [$Btn],
    //   'resize_keyboard' => true
    // ]);

    // $Params = array_merge($Params, [
    //    //'parse_mode'   => 'HTML',
    //    //'parse_mode'   => 'Markdown',
    //    'reply_markup' => $Reply_markup,
    // ]);
    */

    // --- отправка --------------------------
    if($Message){
      $_sendMessage( $ChatId, $MessageText, $ReplyMarkup );
    }

    if($Callback){
      // $_deleteMmessage( $ChatId, $MessageId );
      $_editMessage( $ChatId, $MessageId, $MessageText, $ReplyMarkup );
    }
  }
  
?>