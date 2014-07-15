<?php
namespace develnext\ide\std\ide;

use develnext\ide\IdeManager;
use develnext\ide\IdeTool;
use develnext\tool\Tool;
use php\io\File;
use php\io\IOException;
use php\lang\Process;
use php\lib\str;
use php\swing\Border;
use php\swing\Color;
use php\swing\SwingWorker;
use php\swing\UIElement;
use php\swing\UIRichTextArea;
use php\util\Scanner;

class ConsoleIdeTool extends IdeTool {

    /** @var UIRichTextArea */
    protected $console;

    public function getName() {
        return _('Console');
    }

    public function getIcon() {
        return 'images/icons/script_go.png';
    }

    public function createGui(IdeManager $manager) {
        $console = new UIRichTextArea();
        $console->align = 'client';
        $console->border = Border::createEmpty(0, 0, 0, 0);

        $this->console = $console;
        return $console;
    }

    public function logProcess(Process $process, callable $onEnd = null) {
        $worker = new IdeManagerLogProcessWorker($this->console, $process, $onEnd);
        $worker->execute();
    }

    public function logTool(Tool $tool, File $directory, array $commands, callable $onEnd = null) {
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
            $this->logProcess($tool->execute($directory, $commands, false), $onEnd);
        } catch (IOException $e) {
            $console->appendText(_('Error') . ":\n-----------\n", $console->getStyle('err-b'));
            $console->appendText($e->getMessage() . "\n", $console->getStyle('err'));
            if ($onEnd)
                $onEnd();
        }
    }
}

class IdeManagerLogProcessWorker extends SwingWorker {
    /** @var UIRichTextArea */
    protected $console;

    /** @var Process */
    protected $process;

    /** @var callable */
    protected $onEnd;

    public function __construct(UIRichTextArea $console, Process $process, callable $onEnd = null) {
        $this->console = $console;
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
            $this->publish([$scanner->nextLine()]);
        }

        $err = $this->process->getError();
        $scanner2 = new Scanner($err);
        while ($scanner2->hasNextLine()) {
            $this->publish([$scanner2->nextLine()]);
        }

        $this->publish([]);
    }

    protected function process(array $values) {
        foreach ($values as $value)
            $this->console->appendText($value . "\n", $this->console->getStyle('std'));

        if (!$values && $this->onEnd)
            call_user_func($this->onEnd);
    }
}
