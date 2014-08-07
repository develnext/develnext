<?php
namespace develnext\jdi;

/**
 * Class Method
 * @package develnext\jdi
 */
class Method {

    /**
     * @return Location
     */
    public function location() { return new Location(); }

    /**
     * @return Location[]
     */
    public function allLineLocations() { return []; }

    /**
     * @param $line
     * @return Location[]
     */
    public function locationsOfLine($line) { return []; }

    /**
     * @param int $index
     * @return Location
     */
    public function locationOfCodeIndex($index) { return new Location(); }

    /**
     * @return string binary
     */
    public function bytecodes() { return ''; }

    /**
     * @return bool
     */
    public function isAbstract() { return false; }

    /**
     * @return bool
     */
    public function isStatic() { return false; }

    /**
     * @return bool
     */
    public function isNative() { return false; }

    /**
     * @return bool
     */
    public function isVarArgs() { return false; }

    /**
     * @return bool
     */
    public function isSynchronized() { return false; }

    /**
     * @return bool
     */
    public function isStaticInitializer() { return false; }

    /**
     * @return bool
     */
    public function isObsolete() { return false; }

    /**
     * @return bool
     */
    public function isBridge() { return false; }

    /**
     * @return bool
     */
    public function isConstructor() { return false; }
}
