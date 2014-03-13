<?php
namespace develnext\localization;

use php\swing\UIDialog;

/**
 * Class Localizator
 * @package develnext\localization
 */
class Localizator {

    public function __construct() {

    }

    public function get($pattern) {
        return i18n_format($pattern, func_get_args());
    }

    public function __invoke($el, $text) {
        return i18n_format($text);
    }
}
