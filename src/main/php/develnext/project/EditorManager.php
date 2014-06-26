<?php
namespace develnext\project;

use php\swing\Border;
use php\swing\Font;
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

    public function open(ProjectFile $file) {
        $tab = $this->documents[ $file->hashCode() ];
        if ($tab) {
            $this->tabs->selectedComponent = $tab;
            return;
        }

        $type = $file->getType();

        $editor = $type->createEditor($this->area, $file->getFile(), $this->project);
        if ($editor) {
            $this->tabs->addTab(null, $tab = new UIPanel());

            $tabHead = new UIPanel();
            $tabHead->setLayout('flow');
            $tabHead->opaque = false;


            $xLabel = new UILabel();
            $xLabel->text = (string)$file;
            $xLabel->setIcon($file->getIcon());
            $xLabel->border = Border::createEmpty(0, 0, 0, 5);

            $xButton = new UIButton();
            $xButton->text = 'X';
            $xButton->font = 'Tahoma 8';

            $tabHead->add($xLabel);
            $tabHead->add($xButton);

            $this->tabs->setTabComponentAt($this->tabs->tabCount - 1, $tabHead);

            $tab->add($cmp = $editor->doCreate());
            $cmp->border = Border::createEmpty(4, 4, 4, 4);

            $this->tabs->selectedComponent = $tab;
            $editor->doLoad();

            $this->documents[ $file->hashCode() ] = $tab;
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
}
