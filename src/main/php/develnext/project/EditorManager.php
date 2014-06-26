<?php
namespace develnext\project;

use develnext\editor\Editor;
use php\io\Stream;
use php\swing\Border;
use php\swing\Font;
use php\swing\Image;
use php\swing\UIButton;
use php\swing\UIContainer;
use php\swing\UILabel;
use php\swing\UIPanel;
use php\swing\UITabs;

class EditorManager {
    /** @var Project */
    protected $project;

    /** @var UIContainer */
    protected $area;

    /** @var UITabs */
    protected $tabs;

    /** @var array */
    protected $documents;

    function __construct(Project $project) {
        $this->project = $project;
    }

    protected function createTabHead(ProjectFile $file) {
        $tabHead = new UIPanel();
        $tabHead->setLayout('flow');
        $tabHead->opaque = false;

        $xLabel = new UILabel();
        $xLabel->text = (string)$file;
        $xLabel->font = 'Tahoma 11';
        $xLabel->setIcon($file->getIcon());
        $xLabel->border = Border::createEmpty(0, 0, 0, 6);

        $xButton = new UILabel();

        static $closeIcon;
        if (!$closeIcon)
            $closeIcon = Image::read(Stream::of('res://images/icons/close16.gif'));

        $xButton->setIcon($closeIcon);
        $xButton->cursor = 'hand';
        $xButton->tooltipText = 'Close';

        $xButton->on('click', function() use ($file) {
            $this->removeDocument($file);
        });

        $tabHead->add($xLabel);
        $tabHead->add($xButton);

        return $tabHead;
    }

    public function open(ProjectFile $file) {
        $doc = $this->getDocument($file);
        if ($doc) {
            $this->tabs->selectedComponent = $doc[0];
            return;
        }

        $type = $file->getType();

        $editor = $type->createEditor($file->getFile(), $this);
        if ($editor) {
            $this->tabs->addTab(null, $tab = new UIPanel());
            $this->tabs->setTabComponentAt($this->tabs->tabCount - 1, $tabHead = $this->createTabHead($file));

            $tab->add($cmp = $editor->doCreate());

            $this->tabs->selectedComponent = $tab;
            $editor->doLoad();

            $editor->onChange(function(Editor $editor) use ($tabHead, $file) {
                $text = (string)$file;
                if ($editor->isNotSaved())
                    $text .= ' *';

                $tabHead->getComponent(0)->text = $text;
            });

            $this->documents[ $file->hashCode() ] = [$tab, $editor];
        }

        $this->area->updateUI();
    }

    public function setArea(UIContainer $area) {
        $this->area = $area;

        $tabs = new UITabs();
        $tabs->border = Border::createEmpty(0, 0, 0, 0);
        $tabs->align = 'client';
        $tabs->font = new Font('Tahoma', 0, 11);

        $area->add($tabs);

        $this->tabs = $tabs;
    }

    public function getDocument(ProjectFile $file) {
        return $this->documents[ $file->hashCode() ];
    }

    public function removeDocument(ProjectFile $file) {
        $doc = $this->getDocument($file);
        if ($doc) {
            $this->tabs->remove($doc[0]);
        }
        unset($this->documents[ $file->hashCode() ]);
    }

    public function close() {
        $this->area->remove($this->tabs);
        $this->documents = [];
    }

    public function saveAll() {
        foreach ($this->documents as $doc) {
            /** @var Editor $editor */
            $editor = $doc[1];
            $editor->doSave();
        }
    }
}
