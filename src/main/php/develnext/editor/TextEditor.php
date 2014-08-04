<?php
namespace develnext\editor;

use develnext\lang\Singleton;
use php\io\FileStream;
use php\io\IOException;
use php\lib\char;
use php\lib\str;
use php\swing\Border;
use php\swing\event\KeyEvent;
use php\swing\UIContainer;
use php\swing\UISyntaxTextArea;
use php\util\Scanner;

/**
 * Class TextEditor
 * @package develnext\editor
 */
class TextEditor extends Editor {
    /** @var UISyntaxTextArea */
    protected $syntaxArea;

    /** @var string */
    protected $content;

    /** @var array */
    protected $textHistory = [];

    /** @var string */
    protected $encoding = 'UTF-8';

    protected function onCreate() {
        $syntaxArea = new UISyntaxTextArea();
        $syntaxArea->align = 'client';
        $syntaxArea->syntaxStyle = 'plain/text';
        $syntaxArea->border = Border::createEmpty(0, 0, 0, 0);
        $syntaxArea->tabSize = 2;
        $syntaxArea->iconRowHeaderEnabled = true;
        $syntaxArea->lineNumbersEnabled = true;
        $syntaxArea->antiAliasing = true;

        $syntaxArea->on('keyPress', function(KeyEvent $e){
            if (char::isPrintable($e->keyChar) || $e->keyCode < 32) {
                $this->doChange(true);
            }
        });

        $this->syntaxArea = $syntaxArea;

        return $syntaxArea;
    }

    protected function onLoad() {
        $content = '';
        if ($this->file->exists()) {
            $st = new FileStream($this->file, 'r');
            try {
                $content = $this->content = $st->readFully();
            } finally {
                $st->close();
            }
        }

        $this->syntaxArea->text = $content;
    }

    protected function onSave() {
        $st = new FileStream($this->file, 'w+');
        try {
            $st->write($this->content = $this->syntaxArea->text);
        } finally {
            $st->close();
        }
    }

    protected function onDestroy() {
        $this->syntaxArea->text = '';
        $this->content = '';
    }
}
