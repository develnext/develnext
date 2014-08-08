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

    /**
     * @return bool
     */
    public function isAbstract() { return false; }

    /**
     * @return bool
     */
    public function isFinal() { return false; }

    /**
     * @return bool
     */
    public function isInitialized() { return false; }

    /**
     * @return bool
     */
    public function isPrepared() { return false; }

    /**
     * @return bool
     */
    public function isStatic() { return false; }

    /**
     * @return Method[]
     */
    public function methods() { return []; }

    /**
     * @return Method[]
     */
    public function visibleMethods() { return []; }

    /**
     * @param string $name
     * @return Method[]
     */
    public function methodsByName($name) { return []; }

    /**
     * @return Field[]
     */
    public function fields() { return []; }

    /**
     * @return array
     */
    public function visibleFields() { return []; }

    /**
     * @param $name
     * @return Field[]
     */
    public function fieldByName($name) { return []; }
}
