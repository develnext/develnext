<?php

// UTIL Functions
use php\io\File;
use php\lib\str;
use php\swing\UIDialog;
use develnext\Manager;

define('IS_WIN', str::startsWith(PHP_OS, 'Windows'));
define('ROOT', (new File("."))->getAbsolutePath());

function dump($var) {
    $text = print_r($var, true);
    UIDialog::message($text, 'Debug');
}

function vdump($var) {
    ob_start();
    var_dump($var);
    $text = ob_get_contents();
    ob_end_clean();

    UIDialog::message($text, 'Var Dump');
}

function _($code, array $args = []) {
    return Manager::getInstance()->localizator->get($code, $args);
}

function __($text) {
    return Manager::getInstance()->localizator->translate($text);
}
