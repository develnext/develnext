<?php
namespace DevelNext;

use php\io\Stream;
use php\swing\docking\CControl;
use php\swing\docking\CGrid;
use php\swing\docking\SingleCDockable;
use php\swing\UIElement;
use php\swing\UIReader;
use php\swing\UIWindow;

class Manager {
    /** @var UIReader */
    protected $uiReader;

    /** @var UIWindow[] */
    protected $forms;

    public function __construct() {
        $this->uiReader = new UIReader();
    }

    public function showSplash() {
        $form = $this->getSystemForm('SplashForm.xml');
        $form->getComponent(0)->on('click', function() use ($form) {
            $form->visible = false;
        });
        $form->moveToCenter();
        $form->visible = true;
    }

    public function hideSplash() {
        $this->getSystemForm('SplashForm.xml')->visible = false;
    }

    /**
     * @param string $path
     * @param null $vars
     * @return UIWindow
     */
    public function getSystemForm($path, &$vars = NULL) {
        if ($form = $this->forms[strtolower($path)])
            return $form;

        if ($vars == null)
            $this->uiReader->onRead(null);
        else {
            $this->uiReader->onRead(function(UIElement $e, $var) use (&$vars) {
                $vars[$var] = $e;
            });
        }

        $form = $this->uiReader->read(Stream::create('res://forms/' . $path));
        $this->forms[ strtolower($path) ] = $form;
        return $form;
    }

    public function start() {
        $c = [];
        $form = $this->getSystemForm('MainForm.xml', $c);

        $control = new CControl($form);
        $contentArea = $control->getContentArea();

        $c['content']->add($contentArea);
        $work = $control->createWorkingArea('work');

        $grid = new CGrid($control);
        $grid->add(1, 1, 3, 3, $work);
        $grid->add(0, 0, 1, 4, $one = new SingleCDockable('editor', 'editor', $c['editor']));
        $grid->add(1, 3, 3, 1, new SingleCDockable('console', 'console', $c['console']));

        $contentArea->deploy($grid);
        $control->setTheme('flat');

        /*$button = new UIButton();
        $button->size = [100, 100];
        $work->getComponent()->add($button);*/

        $form->moveToCenter();
        $form->visible = true;
    }
}