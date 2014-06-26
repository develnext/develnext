<?php
namespace {

    use develnext\filetype\PhpFileType;
    use develnext\filetype\SwingFormFileType;
    use develnext\Manager;
    use develnext\filetype\TextFileType;
    use develnext\filetype\DirectoryFileType;
    use develnext\filetype\UnknownFileType;

    use php\io\Stream;
    use php\lang\Module;
    use php\lib\str;
    use php\swing\SwingUtilities;
    use php\swing\Timer;
    use php\swing\UIDialog;
    use php\swing\UIManager;

    // utils functions
    import(Stream::of('res://functions.php'));

    UIManager::setLookAndFeel('com.alee.laf.WebLookAndFeel');

    spl_autoload_register(function($className){
        $name = str::replace($className, '\\', '/') . '.php';
        $module = new Module(Stream::of('res://' . $name));
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
