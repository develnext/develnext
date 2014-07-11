<?php
namespace develnext\ide\std\editor;

use develnext\editor\Editor;
use php\io\Stream;
use php\swing\Color;
use php\swing\Font;
use php\swing\Image;
use php\swing\UIImage;
use php\swing\UILabel;
use php\swing\UIPanel;
use php\swing\UIScrollPanel;

class ImageEditor extends Editor {
    /** @var UIImage */
    protected $image;

    /** @var UIPanel */
    protected $panel;

    /** @var UILabel */
    protected $status;

    protected function onCreate() {
        $uiPanel = new UIScrollPanel();
        $uiPanel->align = 'client';
        $uiPanel->padding = [0, 10];
        $uiPanel->horScrollPolicy = 'always';
        $uiPanel->verScrollPolicy = 'always';

        $image = new UIImage();
        $image->centered = true;
        $image->smooth = true;
        $image->anchors = [];

        $status = new UILabel();
        $status->h = 30;
        $status->align = 'top';
        $status->font = new Font('Tahoma', 0, 11);
        $status->foreground = Color::rgb(127, 127, 127);
        $status->background = Color::decode('#ffffff');
        $uiPanel->add($status);

        $hr = new UIPanel();
        $hr->align = 'top';
        $hr->h = 1;
        $hr->background = $status->foreground;
        $uiPanel->add($hr);
        $uiPanel->add($image);
        $uiPanel->background = Color::decode('#ffffff');

        $this->image = $image;
        $this->panel = $uiPanel;
        $this->status = $status;

        return $uiPanel;
    }

    protected function onLoad() {
        $image = Image::read(Stream::of($this->file));
        $this->image->setImage($image);
        $this->image->size = [$image->width, $image->height];

        $this->image->position = [($this->panel->w / 2) - $image->width/2, ($this->panel->h / 2) - $image->height/2];

        $name = $this->file->getName();
        $this->status->text = "<html>$name [<b>$image->width</b> x <b>$image->height</b>]</html>";
    }

    protected function onSave() {

    }
}
