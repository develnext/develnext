<?php
namespace php\swing\docking;

/**
 * Class CGrid
 * @package php\swing\docking
 */
class CGrid {

    /**
     * @param CControl $control
     */
    public function __construct(CControl $control) { }

    /**
     * @param int $x
     * @param int $y
     * @param int $w
     * @param int $h
     * @param CDockable $dockable
     */
    public function add($x, $y, $w, $h, CDockable $dockable) { }
}