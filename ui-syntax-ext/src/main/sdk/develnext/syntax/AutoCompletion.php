<?php

namespace develnext\syntax;

use php\swing\UITextElement;

/**
 * Class AutoCompletion
 * @package develnext\syntax
 */
class AutoCompletion {

    /**
     * Example: "control SPACE", "control shift SPACE"
     *
     * @var string
     */
    public $triggerKey;

    /**
     * @var int
     */
    public $activationDelay;

    /**
     * @var bool
     */
    public $autoActivation;

    /**
     * @var bool
     */
    public $autoComplete;

    /**
     * @var bool
     */
    public $parameterAssistance;

    /**
     * @var bool
     */
    public $showDescWindow;

    /**
     * @param CompletionProvider $completionProvider
     */
    function __construct(CompletionProvider $completionProvider){ }

    /**
     * @param UITextElement $textComponent
     */
    public function install(UITextElement $textComponent) { }

    /**
     * -
     */
    public function uninstall() { }

    /**
     * @param callable $callback (UIListbox $self, UILabel $template, $value, int $index, bool isSelected, bool cellHasFocus)
     */
    public function onCellRender(callable $callback = null) { }
}
