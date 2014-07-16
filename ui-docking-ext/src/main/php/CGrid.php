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
     * @param float $x
     * @param float $y
     * @param float $w
     * @param float $h
     * @param CDockable $dockable
     */
    public function add($x, $y, $w, $h, CDockable $dockable) { }

    /**
     * @param float $x1
     * @param float $x2
     * @param float $y
     */
    public function addHorizontalDivider($x1, $x2, $y) { }

    /**
     * @param float $x
     * @param float $y1
     * @param float $y2
     */
    public function addVerticalDivider($x, $y1, $y2) { }
}
