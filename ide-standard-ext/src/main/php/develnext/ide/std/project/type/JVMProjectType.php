<?php
namespace develnext\ide\std\project\type;

use develnext\filetype\creator\Creator;
use develnext\ide\std\project\dependency\DirectoryProjectDependency;
use develnext\ide\std\project\dependency\JPHPExtensionDependency;
use develnext\ide\std\project\dependency\MavenProjectDependency;
use develnext\ide\std\project\runner\GradleLauncherRunnerType;
use develnext\project\Project;
use develnext\project\ProjectFile;
use develnext\project\ProjectRunner;
use develnext\project\ProjectType;
use php\io\File;
use php\io\FileStream;
use php\lib\str;

abstract class JVMProjectType extends ProjectType {

    protected static $specialPaths = [
        '/src' => ['{Sources} [/src]', 'images/icons/filetype/sources.png'],
        '/resources' => ['{Resources} [/resources]', 'images/icons/filetype/resources.png'],
        '/resources/forms' => ['{Forms}', 'images/icons/filetype/forms.png'],
        '/resources/images' => ['{Images}', 'images/icons/filetype/images.png'],
    ];

    protected static $availableFileCreatorPaths = [
        'develnext\ide\std\filetype\creator\PhpFileCreator' => ['/src'],
        'develnext\ide\std\filetype\creator\SwingGuiFormCreator' => ['/resources/forms'],
    ];

    function getVersion() {
        return 20140802;
    }

    function getDefaultDependencies() {
        return [
            new MavenProjectDependency('org.develnext', 'jphp-core', '0.4-SNAPSHOT'),
            new JPHPExtensionDependency('spl'),
            new DirectoryProjectDependency(new File('.develnext/resources'))
        ];
    }

    function onCreateProject(Project $project) {
        $project->addRunner($run = new ProjectRunner(new GradleLauncherRunnerType(), 'Launcher', ['command' => 'run']));
        $run->setSingleton(true);
        $project->selectRunner($run);

        $project->addRunner($distZip = new ProjectRunner(new GradleLauncherRunnerType(), 'Dist Zip', [
            'command' => 'distZip',
            'show_dialog_after_building' => true
        ]));
        $distZip->setSingleton(true);

        $project->setFileMark($project->getProjectFile('.develnext/'), 'hidden');
        $project->setFileMark($project->getProjectFile('build/'), 'hidden');
        $project->setFileMark($project->getProjectFile('.gradle/'), 'hidden');

        $this->updateBuildScript($project);
        $this->updateLauncherScript($project);

        $project->getFile('src/')->mkdirs();
        $project->getFile('resources/')->mkdirs();
        $project->getFile('.develnext/resources')->mkdirs();

        $project->getFile('resources/JPHP-INF')->mkdirs();

        $bootstrap = new FileStream($project->getPath('src/bootstrap.php'), 'w+');
        $bootstrap->write('<?php ');
        $bootstrap->close();

        $this->updateConf($project);
    }

    function onUpdateProject(Project $project) {
        $this->updateBuildScript($project);
        $this->updateLauncherScript($project);
        $this->updateConf($project);
    }

    function onRenderFileInTree(ProjectFile $file) {
        $relPath = $file->getRelPath();
        $info = self::$specialPaths[$relPath];

        if ($info) {
            return $file->duplicate(__($info[0]), $info[1]);
        }

        if (str::startsWith($relPath, '/resources/JPHP-INF'))
            return null;

        return $file;
    }

    function isAvailableFileCreator(ProjectFile $file, Creator $creator) {
        $class = get_class($creator);
        if ($info = self::$availableFileCreatorPaths[$class]) {
            foreach($info as $path) {
                if ($path === $file->getRelPath() || str::startsWith($file->getRelPath(), $path . '/')) {
                    return true;
                }
            }
            return false;
        }

        return true;
    }

    protected function updateConf(Project $project) {
        $conf = new FileStream($project->getPath('resources/JPHP-INF/launcher.conf'), 'w+');

        $conf->write("env.debug = 0\n\n");

        $jphpExtensions = [];
        foreach ($project->getDependencies() as $dep) {
            if ($dep instanceof JPHPExtensionDependency) {
                $jphpExtensions[] = $dep->getClassName();
            }
        }

        $conf->write('env.extensions = ' . str::join($jphpExtensions, ', ') . "\n\n");

        $conf->write("bootstrap.file = .app_bootstrap.php\n\n");
        $conf->close();
    }

    protected function updateLauncherScript(Project $project) {
        $file = $project->getFile('.develnext/resources/.app_bootstrap.php');
        $file->getParentFile()->mkdirs();

        $out = new FileStream($file, 'w+');
        try {
            $out->write('<?php
                use php\io\Stream;

                import(Stream::of("res://bootstrap.php"));
            ');
        } finally {
            $out->close();
        }
    }

    protected function updateBuildScript(Project $project) {
        $out = new FileStream($project->getDirectory()->getPath() . '/build.gradle', 'w+');
        try {
            $version = "1.0";
            $name = $project->getName();

            $dirDeps = '';
            foreach ($project->getDependencies() as $dep) {
                if ($dep instanceof DirectoryProjectDependency) {
                    $dirDeps .= 'srcDir \'' . str::replace($dep->getDirectory()->getPath(), '\\', '/') . "'\n";
                }
            }

            $out->write(<<<"DOC"
allprojects {
    apply plugin: 'java'
    apply plugin: 'application'

    group = '$name'
    version = '$version'

    mainClassName = 'php.runtime.launcher.Launcher'

    repositories {
        maven { url 'https://oss.sonatype.org/content/repositories/snapshots/' }
        mavenCentral()
    }

    sourceSets {
        main {
            java {
                srcDir 'src'
            }
            resources {
                srcDir 'src'
                srcDir 'resources'
                $dirDeps
            }
        }
    }
}


DOC
            );

            $out->write("dependencies {\n");

            foreach ($project->getDependencies() as $dep) {
                if ($dep instanceof MavenProjectDependency) {
                    $out->write(
                        "\t\t compile '"
                        . $dep->getGroupId() . ':' . $dep->getArtifactId() . ':' . $dep->getVersion() . "'\n"
                    );
                }
            }

            $out->write("}\n");
        } finally {
            $out->close();
        }
    }
}
