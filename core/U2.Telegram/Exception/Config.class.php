<?php
namespace Tg\Exception;


class Config extends \Tg\Exception{

    // --- --- --- --- --- --- ---
    public function __construct($config, $element) {
        $Message = 'Tg Error: config [' .$config->Description. '] element [' .$element. '] is not defined';
        parent::__construct($Message);
	}
}
?>