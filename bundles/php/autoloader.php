<?php
date_default_timezone_set( 'Europe/Moscow' );

define( 'TGROOT' , realpath( __DIR__ ));
define( 'TGTOP'  , realpath( TGROOT . '/../../' ));
define( 'TGUPPER', realpath( TGROOT . '/../../../../' ));

spl_autoload_register( function( string $ClassName ) {
  if( class_exists( $ClassName )) return;
  $ClassFilePath = TGROOT.'/code/'.str_replace( '\\', '/', $ClassName ).'.class.php';
  if( file_exists( $ClassFilePath )) require_once( $ClassFilePath );
},true,true);

// ----------------------------------------------------------------------------
function tgdump( $Data ) {
  if( $Data === true )      echo 'TRUE' . PHP_EOL;
  elseif( $Data === false ) echo 'FALSE'. PHP_EOL;
  elseif( $Data === null )  echo 'NULL' . PHP_EOL;
  else echo print_r( $Data, 1 ). PHP_EOL;
}

// ----------------------------------------------------------------------------
function tglog( string $FileName, $Data = null){
  $Folder   = TGUPPER.'/log';
  $FilePath = $Folder .'/'. $FileName;
  if(!file_exists($Folder)) { mkdir( $Folder, 0770, true ); chmod( $Folder, 0770 ); }

  //file_put_contents( $FilePath, date('Y-m-d H:i') . PHP_EOL . print_r($Data,1) );
  file_put_contents( $FilePath, date('Y-m-d H:i:s') . PHP_EOL . (
    $Data === true ? 'TRUE' : (
      $Data === false ? 'FALSE' : (
        $Data === null ? 'NULL' : print_r($Data,1)
      )
    )
  ));
}
?>