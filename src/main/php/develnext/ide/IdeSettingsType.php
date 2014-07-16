<?php
namespace develnext\ide;

use php\swing\UIElement;
use php\swing\UIPanel;

abstract class IdeSettingsType {
    /** @var UIPanel */
    protected $editor;

    /** @var IdeSettingsType[] */
    protected $subTypes;

    abstract function getName();
    abstract protected function onCreateEditor(UIPanel $panel);

    abstract function onLoadSettings();
    abstract function onSaveSettings();

    /**
     * @param IdeSettingsType $type
     */
    public function addSubType(IdeSettingsType $type) {
        $this->subTypes[] = $type;
    }

    /**
     * @return IdeSettingsType[]
     */
    public function getSubTypes() {
        return $this->subTypes;
    }

    /**
     * @return UIPanel
     */
    public function getEditor() {
        if ($this->editor)
            return $this->editor;

        $panel = $this->editor = new UIPanel();
        $panel->align = 'client';
        $this->onCreateEditor($panel);

        return $panel;
    }
}
