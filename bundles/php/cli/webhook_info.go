#!/usr/bin/env php
<?php
namespace Tg\Core;
use \Tg\Exception\Fatal;

require_once __DIR__.'/../autoloader.php';
try{
  $Response = Webhook::info();
  tgdump( $Response ).PHP_EOL;
}
catch(\Throwable $e){
  new Fatal($e->getMessage());
}
?>
