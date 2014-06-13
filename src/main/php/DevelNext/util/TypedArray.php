<?php
namespace develnext\util;


use php\lib\items;

class TypedArray implements \Iterator {

    protected $data;

    /**
     * @param array $data
     */
    public function __construct(array $data = null){
        $this->data = $data == null ? array() : $data;
    }

    /**
     * get value by '_arg' key
     * @param mixed $def
     * @return mixed
     */
    public function getDefault($def = null){
        return $this->get('_arg', $def);
    }

    /**
     * @param string $name
     * @return boolean
     */
    public function has($name){
        return isset($this->data[$name]);
    }

    /**
     * @param string $name
     * @param mixed $def
     * @return mixed
     */
    public function get($name, $def = null){
        $value = $this->data[$name];
        return isset($value) ? $value : $def;
    }

    /**
     * return all keys of array
     * @return array
     */
    public function getKeys(){
        return items::keys($this->data);
    }

    /**
     * @param string $name
     * @param string $def
     * @return string
     */
    public function getString($name, $def = ''){
        $value = $this->data[$name];
        return isset($value) ? (string)$value : (string)$def;
    }

    /**
     * @param string $name
     * @param bool $def
     * @return bool
     */
    public function getBoolean($name, $def = false){
        $value = $this->data[$name];
        return isset($value) ?
            $value !== '' && $value !== '0' && $value !== 'false'
            : (boolean)$def;
    }

    /**
     * @param string $name
     * @param int $def
     * @return int
     */
    public function getInteger($name, $def = 0){
        $value = $this->data[$name];
        return isset($value) ? (int)$value : (int)$def;
    }

    /**
     * @param string $name
     * @param float $def
     * @return float
     */
    public function getDouble($name, $def = 0.0){
        $value = $this->data[$name];
        return isset($value) ? (double)$value : (double)$def;
    }

    /**
     * @param $name
     * @return TypedArray
     */
    public function getArray($name){
        return new TypedArray((array)$this->data[$name]);
    }

    public function current(){
        return current($this->data);
    }

    public function next(){
        next($this->data);
    }

    public function key(){
        return key($this->data);
    }

    public function valid(){
        return key($this->data) !== null;
    }

    public function rewind(){
        reset($this->data);
    }
}
