#!/usr/bin/env php
<?php
  require_once __DIR__.'/../core/common.php';

  //$Config   = new U2\Config( ROOT.'/config.json' );
  //$Telegram = new U2\Telegram( $Config );

  //$Response = $Telegram->deleteWebHook();
  $Response = \Tg\Core\Webhook::delete();
  
  tgdump( $Response ) . PHP_EOL;
?>