<?php
namespace develnext\util;

use php\io\Stream;
use php\lib\str;
use php\util\Scanner;

class Config extends TypedArray {

    /** @var Stream */
    protected $stream;

    public function __construct(Stream $stream, $encoding = 'UTF-8') {
        $this->stream = $stream;
        $this->data = [];
        $scanner = new Scanner($stream, $encoding);

        while($scanner->hasNextLine()) {
            $line = $scanner->nextLine();
            list($key, $value) = str::split($line, '=', 2);

            $key = str::trim($key);

            if ($key) {
                $value = str::trim($value);
                $this->data[$key] = $value;
            }
        }
    }

    public function get($key, $def = null) {
        return isset($this->data[$key]) ? $this->data[$key] : $def;
    }

    public function set($key, $value) {
        $this->data[$key] = $value;
    }

    public function save() {
        $this->stream->seek(0);

        foreach($this->data as $key => $value) {
            $this->stream->write("$key = $value\r\n");
        }

        $this->stream->seek(0);
    }
}
