<?php
namespace develnext\client;

/**
 * Class JSend
 * @package develnext\client
 */
class JSend {
    protected $status;
    protected $code;
    protected $data;

    function __construct($status, $data, $code = '') {
        $this->code = $code;
        $this->data = $data;
        $this->status = $status;
    }

    function isSuccess() {
        return $this->code === 'success';
    }

    function getData() {
        return $this->data;
    }

    function getCode() {
        return $this->code;
    }
}
