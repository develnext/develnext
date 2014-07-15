<?php
namespace develnext\ide;

abstract class IdeTool {

    abstract public function getName();

    public function getIcon() {
        return null;
    }

    abstract public function createGui(IdeManager $manager);
}
