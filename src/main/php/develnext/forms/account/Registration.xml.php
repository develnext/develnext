<?php

/** @var \develnext\IDEForm $form */

use develnext\task\RegistrationTask;
use php\swing\event\MouseEvent;

$form->get('btn-cancel')->on('click', function() use ($form){
    $form->hide();
});

$form->get('btn-next')->on('click', function(MouseEvent $e) use ($form) {
    $e->target->enabled = false;
    $form->getWindow()->enabled = false;

    $task = new RegistrationTask();
    $task->execute(function() use ($e, $form) {
        $e->target->enabled = true;
        $form->getWindow()->enabled = true;
    });
});
