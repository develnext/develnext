<?php

/** @var \develnext\IDEForm $form */

$form->get('btn-login')->on('click', function() use ($form) {
    $form->hide(true);
});

$form->get('btn-exit')->on('click', function() use ($form) {
     $form->hide(false);
});
