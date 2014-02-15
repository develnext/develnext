<?php
namespace {

    use php\io\ResourceStream;
    use php\swing\SwingUtilities;
    use php\swing\UIDialog;
    use php\swing\UIElement;
    use php\swing\UIManager;
    use php\swing\UIWindow;
    use php\swing\XmlUIReader;

    UIManager::setLookAndFeel(UIManager::getSystemLookAndFeel());

    SwingUtilities::invokeLater(function(){
        $reader = new XmlUIReader();
        /** @var UIWindow $form */
        $form = $reader->read(new ResourceStream('forms/MainForm.xml'), function(UIElement $e, $var){

        });

        $form->moveToCenter();
        $form->visible = true;
    });
}