<?php
namespace php\swing\docking;
use php\lang\IllegalArgumentException;

/**
 * Class CDockable
 * @package php\swing\docking
 */
abstract class CDockable {

    /**
     * @var bool
     */
    public $visible;

    /**
     * @readonly
     * @var bool
     */
    public $closable;

    /**
     * @readonly
     * @var bool
     */
    public $maximizable;

    /**
     * @readonly
     * @var bool
     */
    public $minimizable;

    /**
     * @var bool
     */
    public $sticky;

    /**
     * @var bool
     */
    public $stickySwitchable;

    /**
     * @var bool
     */
    public $titleShown;

    /**
     * @var bool
     */
    public $stackable;

    /**
     * @var bool
     */
    public $externalizable;

    /**
     * @var bool
     */
    public $normalizeable;

    /**
     * @return bool
     */
    public function isShowing() { return false; }

    /**
     * @return bool
     */
    public function hasParent() { return false; }

    /**
     * @param string $mode - maximized, minimized, normalized, externalized
     */
    public function setExtendedMode($mode) { }

    /**
     * @param CDockable $dockable
     */
    public function setLocationsAside(CDockable $dockable) { }

    /**
     * @param $pos - left, top, right, bottom
     * @throws IllegalArgumentException
     */
    public function setBaseLocation($pos) { }
}
