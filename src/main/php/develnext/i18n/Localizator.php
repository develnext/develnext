<?php
namespace develnext\i18n {

    use develnext\util\Config;
    use php\io\Stream;
    use php\lib\items;
    use php\lib\str;
    use php\util\Flow;
    use php\util\Regex;

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
                $string = str::replace($string, '{' . ($i) . '}', $el);
            }
            return $string;
        }

        public function get($code, array $args = []) {
            $text = $this->messages[$code];
            if (!$text)
                return $args ? self::format($code, $args) : $code;

            return self::format($text, $args);
        }

        public function translate($string) {
            return Regex::of('(\{.+?\})')->with($string)->replaceWithCallback(function(Regex $self){
                $code = str::sub($self->group(), 1);
                $code = str::sub($code, 0, str::length($code) - 1);

                return $this->get($code);
            });
        }

        public function getLang() {
            return $this->lang;
        }
    }

}
