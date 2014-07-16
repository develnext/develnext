<?php

namespace develnext\ide\std\editor;

use develnext\editor\Editor;
use develnext\editor\TextEditor;
use develnext\lang\Singleton;
use php\swing\Border;
use php\swing\Font;
use php\swing\UIDesktopPanel;
use php\swing\UIInternalForm;
use php\swing\UILabel;
use php\swing\UIPanel;
use php\swing\UIReader;
use php\swing\UIScrollPanel;
use php\swing\UISyntaxTextArea;
use php\swing\UITabs;

class SwingFormEditor extends TextEditor {

    /** @var UIScrollPanel */
    protected $designer;

    /** @var UISyntaxTextArea */
    protected $sourceEditor;

    protected function onCreate() {
        $sourceEditor = parent::onCreate();
        $sourceEditor->syntaxStyle = 'text/xml';

        $tabs = new UITabs();
        $tabs->align = 'client';
        $tabs->tabPlacement = 'bottom';
        $tabs->border = Border::createEmpty(0, 0, 0, 0);

        $designer = new UIScrollPanel();
        $designer->border = Border::createEmpty(0, 0, 0, 0);
        $tabs->addTab(_('Designer'), $designer);
        $this->designer = $designer;

        $source = new UIPanel();
        $source->add($sourceEditor);

        $this->sourceEditor = $sourceEditor;

        $tabs->addTab(_('Source'), $source);
        $tabs->font = new Font('Tahoma', 0, 11);
        return $tabs;
    }

    protected function onLoad() {
        parent::onLoad();

        $desktop = new UIDesktopPanel();
        $desktop->align = 'client';
        $this->designer->add($desktop);

        $reader = new UIReader();
        $reader->useInternalForms = true;

        /** @var UIInternalForm $component */
        $component = $reader->read($this->file);
        $component->resizable = true;
        $component->title = 'foobar';
        $desktop->add($component);
        $component->visible = true;
    }

    protected function onSave() {
        parent::onSave();
    }
}
