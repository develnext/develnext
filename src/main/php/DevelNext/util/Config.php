<?php
namespace develnext\util;

use php\io\Stream;
use php\lib\str;
use php\util\Scanner;

class Config extends TypedArray {

    /** @var Stream */
    protected $stream;

    public function __construct(Stream $stream) {
        $this->stream = $stream;
        $this->data = array();
        $scanner = new Scanner($stream, 'UTF-8');

        while($scanner->hasNextLine()) {
            $line = $scanner->nextLine();

            list($key, $value) = str::split($line, '=', 2);

            $this->data[str::trim($key)] = str::trim($value);
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
