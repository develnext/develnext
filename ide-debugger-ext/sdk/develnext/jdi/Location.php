<?php
namespace develnext\jdi;

/**
 * Class Location
 * @package develnext\jdi
 */
class Location {

    /**
     * Gets an identifing name for the source corresponding to this location.
     *
     * @return string
     */
    public function sourceName() { return ''; }

    /**
     * Gets the path to the source corresponding to this location.
     *
     * @return string
     */
    public function sourcePath() { return ''; }

    /**
     * Gets the code position within this location's method.
     *
     * @return int
     */
    public function codeIndex() { return 0; }

    /**
     * Gets the line number of this Location.
     *
     * @return int
     */
    public function lineNumber() { return 0; }

    /**
     * Gets the type to which this Location belongs. Normally the declaring type is a ClassType,
     * but executable locations also may exist within the static initializer of an InterfaceType.
     *
     * @return ReferenceType
     */
    public function declaringType() { return new ReferenceType(); }

    /**
     * Gets the method containing this Location.
     *
     * @return Method
     */
    public function method() { return new Method(); }
}
