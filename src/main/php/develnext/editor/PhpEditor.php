<?php
namespace develnext\editor;

use develnext\lang\Singleton;
use php\io\FileStream;
use php\io\IOException;
use php\swing\UIContainer;
use php\swing\UISyntaxTextArea;

/**
 * Class PhpEditor
 * @package develnext\editor
 */
class PhpEditor extends TextEditor {
    /** @var array */
    protected $textHistory = [];

    protected function onCreate() {
        parent::onCreate();
        $this->syntaxArea->syntaxStyle = 'text/php';

        return $this->syntaxArea;
    }
}
