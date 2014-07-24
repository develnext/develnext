<?php

namespace develnext\ui\decorator;

use develnext\ide\ImageManager;
use php\lang\IllegalArgumentException;
use php\swing\Border;
use php\swing\Font;
use php\swing\Image;
use php\swing\UICombobox;
use php\swing\UIElement;
use php\swing\UILabel;
use php\swing\UIListbox;
use php\swing\UIPanel;

class UIListDecorator extends UIDecorator {
    /** @var UIListbox */
    protected $element;

    /** @var array */
    protected $items = [];

    /** @var string */
    protected $descriptionColor = 'gray';

    /** @var string */
    protected $hintColor = 'gray';

    /**
     * @param UIListbox|UICombobox $element
     * @throws \php\lang\IllegalArgumentException
     */
    public function __construct($element) {
        parent::__construct($element);
        if (!($element instanceof UIListbox) && !($element instanceof UICombobox))
            throw new IllegalArgumentException(get_class($element));

        $element->onCellRender(function($list, UILabel $template, $value, $index, $isSelected){
            $panel = $template;
            $template->opaque = true;

            $item = $this->items[$index];
            $template->text = "<html>$item[title]";
            $template->border = Border::createEmpty(4, 4, 4, 4);

            if ($item['description'])
                $template->text .= "<br> <font color='$this->descriptionColor'><small>$item[description]</small></font>";
            $template->text.= '</html>';

            if ($item['icon'])
                $template->setIcon($item['icon']);

            return $panel;
        });

        for($i = 0; $i < $element->itemCount; $i++) {
            $this->items[] = ['title' => $element->getItem($i)];
        }
    }

    public function clear() {
        $this->element->setItems([]);
        $this->items = [];
    }

    public function setDescriptionColor($htmlColor) {
        $this->descriptionColor = $htmlColor;
    }

    public function add($title, $description, $icon = null) {
        $this->items[] = [
            'title' => $title, 'description' => $description,
            'icon' => $icon ? ($icon instanceof Image ? $icon : ImageManager::get($icon)) : null
        ];
        $this->element->addItem($title);
    }

    public function setIcon($index, $icon) {
        $this->items[$index]['icon'] = $icon ? ImageManager::get($icon) : null;
    }

    public function setDescription($index, $description) {
        $this->items[$index]['description'] = $description;
    }

    /**
     * @return UIListbox
     */
    public function getElement() {
        return $this->element;
    }
}
