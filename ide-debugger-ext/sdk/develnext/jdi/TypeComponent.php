<?php
namespace develnext\jdi;

/**
 * Class TypeComponent
 * @package develnext\jdi
 */
abstract class TypeComponent {

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
     * @return bool
     */
    public function isStatic() { return false; }

    /**
     * @return bool
     */
    public function isFinal() { return false; }

    /**
     * @return bool
     */
    public function isSynthetic() { return false; }

    /**
     * @return bool
     */
    public function isPackagePrivate() { return false; }

    /**
     * @return bool
     */
    public function isPrivate() { return false; }

    /**
     * @return bool
     */
    public function isProtected() { return false; }

    /**
     * @return bool
     */
    public function isPublic() { return false; }

    /**
     * @return int
     */
    public function modifiers() { return 0; }

    /**
     * @return ReferenceType
     */
    public function declaringType() { return new ReferenceType(); }

    /**
     * @return VirtualMachine
     */
    public function virtualMachine() { return new VirtualMachine(); }
}
