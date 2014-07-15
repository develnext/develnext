<?php
namespace php\swing\docking;

use php\swing\Image;
use php\swing\UIElement;

class SingleCDockable extends CDockable {

    /**
     * @var bool
     */
    public $singleTabShown;

    /**
     * @param string $id
     * @param string $title
     * @param UIElement $component
     */
    public function __construct($id, $title, UIElement $component) { }

    /**
     * @param Image $icon
     */
    public function setTitleIcon(Image $icon = null) { }
}
