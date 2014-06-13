<?php

// UTIL Functions

use php\swing\UIDialog;

function dump($var) {
    $text = print_r($var, true);
    UIDialog::message($text, 'Debug');
}
