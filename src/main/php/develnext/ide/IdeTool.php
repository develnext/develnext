<?php
namespace develnext\ide;

use develnext\ui\UITabHead;
use php\swing\UIPanel;

abstract class IdeTool {
    /** @var UITabHead */
    protected $uiTabHead;

    /** @var UIPanel */
    protected $uiTab;

    abstract public function getName();

    public function getIcon() {
        return null;
    }

    abstract public function createGui(IdeManager $manager);

    public function triggerClose() {
        return true;
    }

    /**
     * @param mixed $uiTab
     */
    public function setUiTab($uiTab) {
        $this->uiTab = $uiTab;
    }

    /**
     * @return mixed
     */
    public function getUiTab() {
        return $this->uiTab;
    }

    /**
     * @return UITabHead
     */
    public function getUiTabHead() {
        return $this->uiTabHead;
    }

    /**
     * @param UITabHead $uiTabHead
     */
    public function setUiTabHead($uiTabHead) {
        $this->uiTabHead = $uiTabHead;
    }
}
