<?php
namespace Tg\Exception;

class Fatal /*extends \Tg\Exception*/ {

  // --------------------------------------------------------------------------
  public function __construct( $message ) {
    die( 'Die: ' . $message . '.' . PHP_EOL );
	}
}
?>