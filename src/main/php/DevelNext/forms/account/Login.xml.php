<?php

/** @var \develnext\IDEForm $form */

use develnext\Manager;

$form->get('btn-login')->on('click', function() use ($form) {
    $form->hide(true);
});

$form->get('btn-exit')->on('click', function() use ($form) {
     $form->hide(false);
});

$form->get('btn-reg')->on('click', function(){
    $manager = Manager::getInstance();
    $manager->getSystemForm('account/Registration.xml')->showModal();
});
