<?php

// UTIL Functions

use php\swing\UIDialog;

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
