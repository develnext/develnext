<?php
namespace develnext\jdi;
use php\lang\Process;

/**
 * Class VirtualMachine
 * @package develnext\jdi
 */
class VirtualMachine {
    const TRACE_NONE = 0;
    const TRACE_SENDS = 1;
    const TRACE_RECEIVES = 2;
    const TRACE_EVENTS = 4;
    const TRACE_REFTYPES = 8;
    const TRACE_OBJREFS = 16;
    const TRACE_ALL = 16777215;

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
     * @return string
     */
    public function name() { return ''; }

    /**
     * @return string
     */
    public function description() { return ''; }

    /**
     * @return string
     */
    public function version() { return ''; }

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

    /**
     * @return Process
     */
    public function process() { return new Process([]); }

    /**
     * @return ThreadReference[]
     */
    public function allThreads() { return []; }

    /**
     * @param int $mode - self::TRACE_* constants
     */
    public function setDebugTraceMode($mode) { }
}
