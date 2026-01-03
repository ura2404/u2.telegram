<?php
namespace Tg\Exception;

class Config extends \Exception{

  // --------------------------------------------------------------------------
  public function __construct( Config $config, $element ) {
    $Message = 'Error: config [' . $config->Description . '] element [' . $element . '] is not defined';
    parent::__construct( $Message );
	}
}
?>