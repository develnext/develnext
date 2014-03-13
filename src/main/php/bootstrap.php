<?php
namespace {

    use develnext\Manager;
    use php\io\IOException;
    use php\io\Stream;
    use php\swing\SwingUtilities;
    use php\swing\Timer;
    use php\swing\UIDialog;
    use php\swing\UIManager;

    UIManager::setLookAndFeel('com.alee.laf.WebLookAndFeel');

    spl_autoload_register(function($className){
        $name = implode('/', explode('\\', $className)) . '.php';
        import(Stream::create('res://' . $name), $name);
    });

    SwingUtilities::invokeLater(function(){
        $initializer = new Manager();
        $initializer->showSplash();
        $initializer->start();

        $t = new Timer(2000, function() use ($initializer) {
            $initializer->hideSplash();
        });
        $t->start();
    });
}
