<?php

namespace develnext\ide\std\editor;

use develnext\editor\Editor;
use develnext\editor\TextEditor;
use develnext\lang\Singleton;
use DevelNext\swing\ComponentMover;
use DevelNext\swing\ComponentResizer;
use DevelNext\swing\DesignContainer;
use php\swing\Border;
use php\swing\Font;
use php\swing\UIContainer;
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

    protected function updateForm() {
        $desktop = new UIScrollPanel();
        $desktop->align = 'client';
        $desktop->border = Border::createEmpty(0,0,0,0);

        $this->designer->removeAll();
        $this->designer->add($desktop);

        $reader = new UIReader();

        /** @var UIContainer $form */
        $form = $reader->read($this->file);

        $panel = new UIPanel();
        $panel->align = 'client';

        $cr = new ComponentResizer();
        $cm = new ComponentMover();

        foreach($form->getComponents() as $component) {
            $r = new DesignContainer();
            $r->size = $component->size;
            $r->position = $component->position;
            $r->add($component);

            $panel->add($r);

            $cr->registerComponent($r);
            $cm->registerComponent($r);
        }

        $desktop->add($panel);

        $panel->on('click', function() use ($panel) {
            /** @var DesignContainer $r */
            foreach($panel->getComponents() as $r) {
                $r->selected = false;
            }
        });
    }

    protected function onLoad() {
        parent::onLoad();

        $this->updateForm();
    }

    protected function onSave() {
        parent::onSave();

        $this->updateForm();
    }
}
