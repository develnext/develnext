<?php
namespace develnext\model;

class AccessToken {
    public $id;
    public $expires_in;

    public function __construct($id, $expires_in) {
        $this->id = $id;
        $this->expires_in = $expires_in;
    }
}
