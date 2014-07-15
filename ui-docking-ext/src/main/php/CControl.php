<?php
namespace php\swing\docking;

use php\io\File;
use php\io\Stream;
use php\swing\Image;
use php\swing\UIForm;
use php\swing\UIUnknown;

/**
 * Class CControl
 * @package php\swing\docking
 */
class CControl {

    /**
     * @param UIForm $form
     */
    public function __construct(UIForm $form = null) { }

    /**
     * @param string $theme
     */
    public function setTheme($theme) { }

    /**
     * @return CContentArea
     */
    public function getContentArea() { }

    /**
     * @param $uniqueId
     * @return CGridArea
     */
    public function createWorkingArea($uniqueId) { }

    /**
     * @param CDockable $dockable
     * @param null|string $uniqueId
     */
    public function addDockable(CDockable $dockable, $uniqueId = null) { }

    /**
     * @param Stream|File|string $stream
     */
    public function write($stream) { }

    /**
     * @param Stream|File|string $stream
     */
    public function read($stream) { }

    /**
     * @param Stream|File|string $stream
     */
    public function writeXml($stream) { }

    /**
     * @param Stream|File|string $stream
     */
    public function readXml($stream) { }

    /**
     * @param string $name
     * @param Image $icon
     */
    public function setIcon($name, Image $icon) { }

    /**
     * @param string $name
     * @param Image $icon
     */
    public function setIconTheme($name, Image $icon) { }

    /**
     * @param string $name
     * @param Image $icon
     */
    public function setIconClient($name, Image $icon) { }
}
