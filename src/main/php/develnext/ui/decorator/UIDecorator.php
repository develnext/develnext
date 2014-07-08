<?php

namespace develnext\ui\decorator;

use php\swing\UIElement;

abstract class UIDecorator {

    /** @var UIElement */
    protected $element;

    public function __construct(UIElement $element) {
        $this->element = $element;
    }
}
