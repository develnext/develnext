<?php
namespace develnext\jdi;

/**
 * Class VirtualMachine
 * @package develnext\jdi
 */
class VirtualMachine {

    private function __construct() { }

    /**
     * @param $address
     * @param int $timeout
     * @return \develnext\jdi\VirtualMachine
     */
    public static function of($address, $timeout = 5000) {
        return new VirtualMachine();
    }

    /**
     *
     */
    public function suspend() { }

    /**
     *
     */
    public function resume() { }

    /**
     * @param int $code
     */
    public function halt($code = 0) { }
}
