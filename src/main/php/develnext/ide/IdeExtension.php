<?php
namespace develnext\ide;

abstract class IdeExtension {

    abstract public function onRegister(IdeManager $manager);
}
