<?php
namespace php\swing;

/**
 * Class UISyntaxTextArea
 * @package php\swing
 */
class UISyntaxTextArea extends UITextElement {
    /**
     * Example: text/php, text/css
     * @var string
     */
    public $syntaxStyle;

    /**
     * @var bool
     */
    public $lineNumbersEnabled;

    /**
     * @var bool
     */
    public $iconRowHeaderEnabled;

    /**
     * @var int
     */
    public $tabSize;

    /**
     * @var bool
     */
    public $codeFolding;

    /**
     * @var bool
     */
    public $antiAliasing;
}
