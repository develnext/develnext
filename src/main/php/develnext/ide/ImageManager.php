<?php

namespace develnext\ide;

use develnext\lang\Singleton;
use php\io\IOException;
use php\io\Stream;
use php\lib\str;
use php\swing\Image;

/**
 * Class ImageManager
 * @package develnext\ide
 */
class ImageManager {
    use Singleton;

    /** @var Image */
    protected $images;

    /**
     * @param $path
     * @return null|Image
     */
    protected function _get($path) {
        if (str::pos($path, '://') === -1)
            $path = 'res://' . $path;

        if ($r = $this->images[$path])
            return $r;

        try {
            $img = Image::read(Stream::of($path));
            $this->images[$path] = $img;
            return $img;
        } catch (IOException $e) {
            return null;
        }
    }

    public static function get($path) {
        $manager = ImageManager::getInstance();
        return $manager->_get($path);
    }
}
