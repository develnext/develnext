<?php
namespace develnext\jdi;

/**
 * Class Field
 * @package develnext\jdi
 */
class Field extends TypeComponent {

    /**
     * @return string
     */
    public function typeName() { return ''; }

    /**
     * @return bool
     */
    public function isEnumConstant() { return false; }

    /**
     * @return bool
     */
    public function isTransient() { return false; }

    /**
     * @return bool
     */
    public function isVolatile() { return false; }
}
