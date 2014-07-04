<?php
namespace develnext\ide\std\editor;

use develnext\editor\TextEditor;
use develnext\lang\Singleton;
use develnext\syntax\AutoCompletion;
use develnext\syntax\CompletionProvider;
use develnext\syntax\PhpCompletionProvider;
use php\io\FileStream;
use php\io\IOException;
use php\io\Stream;
use php\swing\Font;
use php\swing\UIContainer;
use php\swing\UILabel;
use php\swing\UIListbox;
use php\swing\UISyntaxTextArea;
use php\util\Regex;

/**
 * Class PhpEditor
 * @package develnext\editor
 */
class PhpEditor extends TextEditor {
    protected function onCreate() {
        parent::onCreate();
        $this->syntaxArea->syntaxStyle = 'text/php';
        $this->syntaxArea->codeFolding = true;

        $provider = new CompletionProvider();
        $provider->addTemplateCompletion('class', '**class** [name] { ...', "class \${name} {\n   \${cursor}\n}");
        $provider->addTemplateCompletion('function', '**function** [name] (...) { ', "function \${name}(\${parameters}) {\n   \${cursor}\n}");
        $provider->addTemplateCompletion('if', '**if** (cond) { ... }', "if (\${condition}) {\n   \${cursor} \n}");
        $provider->addTemplateCompletion(
            'for', '**for** (i = 0; i < count; i++) { ...',
            "for (\${\$i} = 0; \${\$i} < \${count}; \${\$i}++) {\n   \${cursor} \n}"
        );

        $autoCompletion = new AutoCompletion($provider);
        $autoCompletion->autoComplete = true;
        $autoCompletion->autoActivation = true;
        $autoCompletion->parameterAssistance = true;
        $autoCompletion->onCellRender(function(UIListbox $listbox, UILabel $template){
            $value = $template->text;
            $value = Regex::of('\*\*(.+?)\*\*')->with($value)->replace('<b>$1</b>');

            $template->text = "<html>$value</html>";
            $template->font = new Font('Consolas', $template->font->style, $template->font->size);
        });

        $autoCompletion->install($this->syntaxArea);

        return $this->syntaxArea;
    }
}
