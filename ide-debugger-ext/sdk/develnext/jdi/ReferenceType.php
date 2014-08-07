<?php
namespace develnext\jdi;

/**
 * Class ReferenceType
 * @package develnext\jdi
 */
class ReferenceType {
    /**
     * @return string
     */
    public function name() { return ''; }

    /**
     * @return string
     */
    public function signature() { return ''; }

    /**
     * @return string
     */
    public function genericSignature() { return ''; }

    /**
     * @return string
     */
    public function sourceName() { return ''; }

    /**
     * @param $count
     * @return ObjectReference[]
     */
    public function instances($count) { return []; }
}
