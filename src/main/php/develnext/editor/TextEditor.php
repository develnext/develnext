<?php
namespace develnext\editor;

use develnext\lang\Singleton;
use php\io\FileStream;
use php\io\IOException;
use php\lib\char;
use php\swing\Border;
use php\swing\event\KeyEvent;
use php\swing\UIContainer;
use php\swing\UISyntaxTextArea;

/**
 * Class TextEditor
 * @package develnext\editor
 */
class TextEditor extends Editor {
    /** @var UISyntaxTextArea */
    protected $syntaxArea;

    /** @var array */
    protected $textHistory = [];

    protected function onCreate() {
        $syntaxArea = new UISyntaxTextArea();
        $syntaxArea->align = 'client';
        $syntaxArea->syntaxStyle = 'plain/text';
        $syntaxArea->border = Border::createEmpty(0, 0, 0, 0);
        $syntaxArea->tabSize = 2;
        $syntaxArea->iconRowHeaderEnabled = true;
        $syntaxArea->lineNumbersEnabled = true;

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
            $content = $st->readFully();
            $st->close();
        }

        $this->syntaxArea->text = $content;
    }

    protected function onSave() {
        $st = new FileStream($this->file, 'w+');
        try {
            $st->write($this->syntaxArea->text);
            $st->close();
        } catch (IOException $e) {
            $st->close();
        }
    }

    protected function onDestroy() {
        $this->syntaxArea->text = '';
    }
}
