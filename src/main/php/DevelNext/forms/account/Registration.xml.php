<?php

/** @var \develnext\IDEForm $form */

use develnext\Manager;
use develnext\task\RegistrationTask;
use php\lang\Thread;

$form->get('btn-cancel')->on('click', function() use ($form){
    $form->hide();
});

$form->get('btn-next')->on('click', function(){
    $task = new RegistrationTask();
    $task->execute();
});
