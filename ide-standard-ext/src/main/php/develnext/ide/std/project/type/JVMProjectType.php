<?php
namespace develnext\ide\std\project\type;

use develnext\ide\std\project\dependency\JPHPExtensionDependency;
use develnext\ide\std\project\dependency\MavenProjectDependency;
use develnext\project\Project;
use develnext\project\ProjectFile;
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

    function getDefaultDependencies() {
        return [
            new MavenProjectDependency('org.develnext', 'jphp-core', '0.4-SNAPSHOT'),
            new JPHPExtensionDependency('spl')
        ];
    }

    function onCreateProject(Project $project) {
        $project->setFileMark($project->getProjectFile('.develnext/'), 'hidden');
        $project->setFileMark($project->getProjectFile('build/'), 'hidden');
        $project->setFileMark($project->getProjectFile('.gradle/'), 'hidden');

        $this->updateBuildScript($project);

        $project->getFile('src/')->mkdirs();
        $project->getFile('resources/')->mkdirs();

        $project->getFile('resources/JPHP-INF')->mkdirs();

        $bootstrap = new FileStream($project->getPath('src/bootstrap.php'), 'w+');
        $bootstrap->write('<?php ');
        $bootstrap->close();

        $this->updateConf($project);
    }

    function onUpdateProject(Project $project) {
        $this->updateBuildScript($project);
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

        $conf->write("bootstrap.file = bootstrap.php\n\n");
        $conf->close();
    }

    protected function updateBuildScript(Project $project) {
        $out = new FileStream($project->getDirectory()->getPath() . '/build.gradle', 'w+');

        $version = "1.0";
        $name = $project->getName();

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
            }
        }
    }
}


DOC
);

        $out->write("dependencies {\n");

        foreach($project->getDependencies() as $dep) {
            if ($dep instanceof MavenProjectDependency) {
                $out->write(
                    "\t\t compile '"
                    . $dep->getGroupId() . ':' . $dep->getArtifactId() . ':' . $dep->getVersion() . "'\n"
                );
            }
        }

        $out->write("}\n");
        $out->close();
    }
}
