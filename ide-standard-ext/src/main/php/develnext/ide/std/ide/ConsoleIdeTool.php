<?php
namespace develnext\ide\std\ide;

use develnext\ide\components\UIMessages;
use develnext\ide\IdeManager;
use develnext\ide\IdeTool;
use develnext\ide\ImageManager;
use develnext\tool\Tool;
use php\io\File;
use php\io\IOException;
use php\lang\Process;
use php\lib\str;
use php\swing\Border;
use php\swing\Color;
use php\swing\SwingWorker;
use php\swing\UIButton;
use php\swing\UIElement;
use php\swing\UIPanel;
use php\swing\UIRichTextArea;
use php\util\Flow;
use php\util\Scanner;

class ConsoleIdeTool extends IdeTool {
    /** @var UIRichTextArea */
    protected $console;

    /** @var UIPanel */
    protected $buttons;

    /** @var Process */
    protected $process;

    public function getName() {
        return _('Console');
    }

    public function getIcon() {
        return 'images/icons/script_go.png';
    }

    public function createGui(IdeManager $manager) {
        /** @var UIPanel $panel */
        $panel = $manager->newElement(<<<'EL'
<ui-panel align="client">
    <ui-panel align="left" w="36" padding="3" group="buttons" />
    <ui-rich-textarea align="client" border="empty" group="console" />
</ui-panel>
EL
);
        $this->console = $panel->getComponentByGroup('console');
        $this->buttons = $panel->getComponentByGroup('buttons');

        return $panel;
    }

    /**
     * @param $group
     * @param $icon
     * @param string $hint
     * @return UIButton
     */
    public function addButton($group, $icon, $hint = '') {
        $btn = new UIButton();
        $btn->setIcon($icon);
        $btn->group = $group;
        $btn->align = 'top';
        $btn->h = 30;
        $btn->cursor = 'hand';
        $btn->tooltipText = $hint;

        $this->buttons->add($btn);
        $btn->on('click', function($e) use ($group) {
            $this->trigger("btn-$group", [$e]);
        });
        return $btn;
    }

    /**
     * @param $group
     * @return NULL|UIButton
     */
    public function getButton($group) {
        return $this->buttons->getComponentByGroup($group);
    }

    public function addSeparator() {
        $hr = new UIPanel();
        $hr->align = 'top';
        $hr->h = 3;
        $this->buttons->add($hr);

        $hr = new UIPanel();
        $hr->align = 'top';
        $hr->h = 1;
        $hr->background = Color::decode('#9E9E9E');
        $this->buttons->add($hr);

        $hr = new UIPanel();
        $hr->align = 'top';
        $hr->h = 3;
        $this->buttons->add($hr);
    }

    public function logProcess(Process $process) {
        $this->process = $process;
        $worker = new ConsoleIdeTool_LogProcessWorker($this, $process);
        $this->trigger('log');
        $worker->execute();
    }

    public function logTool(Tool $tool, File $directory, array $commands) {
        $console = $this->console;

        $console->text = '';

        $dir = $directory->getPath();

        $style = $console->addStyle('run');
        $style->foreground = Color::decode('#167E16');

        $style = $console->addStyle('std');
        $style->foreground = Color::decode('#000000');

        $style = $console->addStyle('err');
        $style->foreground = Color::decode('#C40000');

        $style = $console->addStyle('err-b', $style);
        $style->bold = true;

        $console->appendText(
            '> ' . $tool->getName() . ' ' . str::join($commands, ' ') . " (for $dir) ... \n\n",
            $console->getStyle('run')
        );

        try {
            $this->logProcess($tool->execute($directory, $commands, false));
        } catch (IOException $e) {
            if ($this->trigger('exception', [$e])) {
                $console->appendText(_('Error') . ":\n-----------\n", $console->getStyle('err-b'));
                $console->appendText($e->getMessage() . "\n", $console->getStyle('err'));
            }
            $this->trigger('finish');
        }
    }

    public function appendText($text, $class = 'std') {
        $this->console->appendText($text, $this->console->getStyle($class));
        if ($this->process->getExitValue() !== NULL)
            $this->trigger('finish');
    }

    /**
     * @return Process
     */
    public function getProcess() {
        return $this->process;
    }
}

class ConsoleIdeTool_LogProcessWorker extends SwingWorker {
    /** @var ConsoleIdeTool */
    protected $tool;

    /** @var Process */
    protected $process;

    /** @var callable */
    protected $onEnd;

    public function __construct(ConsoleIdeTool $tool, Process $process, callable $onEnd = null) {
        $this->tool = $tool;
        $this->process = $process;
        $this->onEnd = $onEnd;
    }

    /**
     * @return mixed
     */
    protected function doInBackground() {
        $st = $this->process->getInput();
        $scanner = new Scanner($st);
        while ($scanner->hasNextLine()) {
            $this->publish([['std', $scanner->nextLine()]]);
        }

        $err = $this->process->getError();
        $scanner2 = new Scanner($err);
        while ($scanner2->hasNextLine()) {
            $this->publish([['err', $scanner2->nextLine()]]);
        }

        $this->publish([]);
    }

    protected function process(array $values) {
        if ($values) {
            foreach ($values as $value) {
                if (is_array($value))
                    $this->tool->appendText($value[1] . "\n", $value[0]);
                else
                    $this->tool->appendText($value . "\n", 'std');
            }
        }

        if (!$values && $this->onEnd)
            call_user_func($this->onEnd);
    }
}
