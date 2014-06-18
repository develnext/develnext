<?php
namespace develnext\i18n;

use develnext\util\Config;
use php\io\Stream;
use php\lib\items;
use php\lib\str;
use php\util\Flow;

class Localizator {
    protected $lang;
    protected $messages;

    public function __construct($lang) {
        $this->lang = $lang;
        $this->messages = [];
    }

    public function append(Stream $source) {
        $config = new Config($source);
        $this->messages = Flow::of($this->messages)
            ->append($config->all())
            ->withKeys()
            ->toArray();
    }

    public static function format($string, array $args = []) {
        foreach($args as $i => $el) {
            $string = str::replace($string, '{' . ($i+1) . '}', $el);
        }
        return $string;
    }

    public function translate($code, array $args = []) {
        $text = $this->messages[$code];
        if (!$text)
            return $code;

        return self::format($text, $args);
    }

    public function getLang() {
        return $this->lang;
    }
}
