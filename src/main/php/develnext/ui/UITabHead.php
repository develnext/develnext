<?php
namespace develnext\ui;

use php\io\Stream;
use php\swing\Border;
use php\swing\Image;
use php\swing\UILabel;
use php\swing\UIPanel;
use php\swing\UITabs;

class UITabHead extends UIPanel {

    function __construct(UITabs $tabs, $tab, $object, Image $icon = null) {
        parent::__construct();
        $this->addAllowedEventType('close');

        $this->setLayout('flow');
        $this->opaque = false;

        $xLabel = new UILabel();
        $xLabel->text = (string)$object;
        $xLabel->font = 'Tahoma 11';
        $xLabel->setIcon($icon);
        $xLabel->border = Border::createEmpty(0, 0, 0, 6);

        $xLabel->on('click', function() use ($tabs, $tab) {
            $tabs->selectedComponent = $tab;
        });

        $xButton = new UILabel();

        static $closeIcon;
        if (!$closeIcon)
            $closeIcon = Image::read(Stream::of('res://images/icons/close16.gif'));

        $xButton->setIcon($closeIcon);
        $xButton->cursor = 'hand';
        $xButton->tooltipText = 'Close';

        $xButton->on('click', function() {
            $this->trigger('close');
        });

        $this->add($xLabel);
        $this->add($xButton);
    }
}
