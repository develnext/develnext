<?php
namespace php\swing\docking;

use php\swing\UIPanel;

/**
 * Class CContentArea
 * @package php\swing\docking
 */
class CContentArea extends UIPanel {

    /**
     * @param CControl $control
     * @param string $uniqueId
     */
    public function __construct(CControl $control, $uniqueId) { }

    /**
     * @param CGrid $grid
     */
    public function deploy(CGrid $grid) { }
}