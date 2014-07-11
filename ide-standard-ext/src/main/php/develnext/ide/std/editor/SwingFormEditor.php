<?php

namespace develnext\ide\std\editor;

use develnext\editor\Editor;
use develnext\editor\TextEditor;
use develnext\lang\Singleton;
use php\swing\Border;
use php\swing\Font;
use php\swing\UIPanel;
use php\swing\UISyntaxTextArea;
use php\swing\UITabs;

class SwingFormEditor extends TextEditor {

    protected function onCreate() {
        $sourceEditor = parent::onCreate();
        $sourceEditor->syntaxStyle = 'text/xml';

        $tabs = new UITabs();
        $tabs->align = 'client';
        $tabs->tabPlacement = 'bottom';
        $tabs->border = Border::createEmpty(1, 1, 2, 1);

        $designer = new UIPanel();
        $tabs->addTab(_('Designer'), $designer);

        $source = new UIPanel();
        $source->add($sourceEditor);

        $tabs->addTab(_('Source'), $source);
        $tabs->font = new Font('Tahoma', 0, 11);
        return $tabs;
    }

    protected function onLoad() {
        parent::onLoad();
    }

    protected function onSave() {
        parent::onSave();
    }
}
