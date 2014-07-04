<?php
namespace develnext\ide\std\editor;

use develnext\editor\TextEditor;
use develnext\lang\Singleton;
use php\io\FileStream;
use php\io\IOException;
use php\swing\UIContainer;
use php\swing\UISyntaxTextArea;

/**
 * Class PhpEditor
 * @package develnext\editor
 */
class GroovyEditor extends TextEditor {
    protected function onCreate() {
        parent::onCreate();
        $this->syntaxArea->syntaxStyle = 'text/groovy';

        return $this->syntaxArea;
    }
}
