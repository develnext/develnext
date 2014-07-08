<?php
namespace develnext\ide\std\project\type;

class ConsoleProjectType extends JVMProjectType {

    function getName() {
        return 'Console';
    }

    protected function getIcon() {
        return 'images/icons/projecttype/console';
    }
}
