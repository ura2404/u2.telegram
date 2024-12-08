<?php
date_default_timezone_set('Europe/Moscow');

define( 'TGROOT', realpath(__DIR__.'/../..') );

spl_autoload_register(function($ClassName){
  if(class_exists($ClassName)) return;

  if ( strpos( $ClassName, 'Tg\\' ) === 0 ) $ClassFilePath = TGROOT.'/core/U2.Telegram'.str_replace( '\\', '/', substr( $ClassName, 2 ) ).'.class.php';
                                       else $ClassFilePath = TGROOT.'/core/'.           str_replace( '\\', '/', $ClassName ).             '.class.php';

  // tgdump( 'TGROOT >'.TGROOT.'<' );
  // tgdump( '$ClassName >'.$ClassName.'<');
  // tgdump( '$ClassFilePath >'.$ClassFilePath.'<' );

  if(file_exists($ClassFilePath)) require_once($ClassFilePath);
},true,true);

// ----------------------------------------------------------------------------
function tgdump($Data){
  if($Data === true)      echo 'TRUE' . PHP_EOL;
  elseif($Data === false) echo 'FALSE'. PHP_EOL;
  elseif($Data === null)  echo 'NULL' . PHP_EOL;
  else echo print_r($Data,1). PHP_EOL;
}

// ----------------------------------------------------------------------------
function _log( $FileName, $Data ){
  $Folder   = TGROOT.'/log';
  $FilePath = $Folder .'/'. $FileName;
  if(!file_exists($Folder) ) mkdir( $Folder, 0700, true );
  file_put_contents( $FilePath, date('Y-m-d H:i') . PHP_EOL . print_r($Data,1) );  
}
?>