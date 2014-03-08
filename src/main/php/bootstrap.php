<?php
namespace {

    use php\io\Stream;
    use php\swing\SwingUtilities;
    use php\swing\Timer;
    use php\swing\UIManager;
    use DevelNext\Manager;

    UIManager::setLookAndFeel('com.alee.laf.WebLookAndFeel');

    spl_autoload_register(function($className){
        import(Stream::create('res://DevelNext/Manager.php'), 'DevelNext/Manager.php');
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
