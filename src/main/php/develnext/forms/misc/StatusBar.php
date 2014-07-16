<?php

/** @var IDEForm $form */

use develnext\IDEForm;

$form->getWindow()->on('click', function() use ($form) {
    $form->hide();
});
