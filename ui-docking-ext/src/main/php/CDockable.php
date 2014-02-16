<?php
namespace php\swing\docking;

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
}