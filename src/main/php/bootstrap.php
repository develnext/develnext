<?php
namespace {

    use develnext\Manager;
    use php\io\IOException;
    use php\io\Stream;
    use php\lang\Module;
    use php\lib\str;
    use php\swing\SwingUtilities;
    use php\swing\Timer;
    use php\swing\UIDialog;
    use php\swing\UIManager;

    // utils functions
    $module = new Module(Stream::of('res://functions.php'));
    $module->call();

    UIManager::setLookAndFeel('com.alee.laf.WebLookAndFeel');

    spl_autoload_register(function($className){
        $name = str::replace($className, '\\', '/') . '.php';
        try {
            $module = new Module(Stream::of('res://' . $name));
        } catch (IOException $e) {
            return false;
        }
        $module->call();
    });

    SwingUtilities::invokeLater(function(){
        $initializer = Manager::getInstance();

        $initializer->showSplash();
        $initializer->start();

        $t = new Timer(2000, function() use ($initializer) {
            $initializer->hideSplash();
        });
        $t->repeat = false;
        $t->start();
    });
}
