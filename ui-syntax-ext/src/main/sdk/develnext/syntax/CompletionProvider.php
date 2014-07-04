<?php
namespace develnext\syntax;
use php\io\File;
use php\io\Stream;

/**
 * Class CompletionProvider
 * @package develnext\syntax
 */
class CompletionProvider {

    /**
     * @param string $replacementText
     * @param null|string $shortDesc
     * @param null|string $summary
     */
    public function addCompilation($replacementText, $shortDesc = null, $summary = null) { }

    /**
     * @param string $inputText
     * @param string $definitionString
     * @param string $template
     */
    public function addTemplateCompletion($inputText, $definitionString, $template) { }

    /**
     * @param string $name
     * @param string $type
     */
    public function addVariableCompletion($name, $type) { }

    /**
     * @param string|File|Stream $source
     */
    public function loadFromXml($source) { }
}
