<?php
namespace develnext\ide\components;

use php\swing\UIDialog;

final class UIMessages {

    private function __construct() { }

    public static function error($text) {
        UIDialog::message($text, _('ide.components.messages.error.title'), UIDialog::ERROR_MESSAGE);
    }
}
