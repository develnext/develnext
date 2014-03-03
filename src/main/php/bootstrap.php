<?php
namespace {

    use php\io\ResourceStream;
    use php\swing\docking\CControl;
    use php\swing\docking\CGrid;
    use php\swing\docking\SingleCDockable;
    use php\swing\SwingUtilities;
    use php\swing\UIButton;
    use php\swing\UIElement;
    use php\swing\UIManager;
    use php\swing\UIWindow;
    use php\swing\XmlUIReader;

    UIManager::setLookAndFeel(UIManager::getSystemLookAndFeel());

    SwingUtilities::invokeLater(function(){
        $reader = new XmlUIReader();
        $c = [];

        /**
         * @var UIWindow $form
         */
        $form = $reader->read(new ResourceStream('forms/MainForm.xml'), function(UIElement $e, $var) use (&$c) {
            $c[$var] = $e;
        });

        $control = new CControl($form);
        $contentArea = $control->getContentArea();

        $c['content']->add($contentArea);
        $work = $control->createWorkingArea('work');

        $grid = new CGrid($control);
        $grid->add(1, 1, 3, 3, $work);
        $grid->add(0, 0, 1, 4, $one = new SingleCDockable('editor', 'editor', $c['editor']));
        $grid->add(1, 3, 3, 1, new SingleCDockable('console', 'console', $c['console']));

        $contentArea->deploy($grid);
        $control->setTheme('develnext');

        /*$button = new UIButton();
        $button->size = [100, 100];
        $work->getComponent()->add($button);*/

        $form->moveToCenter();
        $form->visible = true;
    });
}
