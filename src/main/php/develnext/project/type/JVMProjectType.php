<?php
namespace develnext\project\type;

use develnext\project\dependency\MavenProjectDependency;
use develnext\project\Project;
use develnext\project\ProjectFile;
use develnext\project\ProjectType;
use php\io\FileStream;
use php\lib\str;

abstract class JVMProjectType extends ProjectType {

    protected static $specialPaths = [
        '/src' => ['Sources', 'images/icons/filetype/sources.png'],
        '/resources' => ['Resources', 'images/icons/filetype/resources.png'],
        '/resources/forms' => [null, 'images/icons/filetype/forms.png'],
        '/resources/images' => [null, 'images/icons/filetype/images.png'],
    ];

    function getDefaultDependencies() {
        return [
            new MavenProjectDependency('org.develnext', 'jphp-core', '0.4-SNAPSHOT')
        ];
    }

    function onCreateProject(Project $project) {
        $this->updateBuildScript($project);

        $project->getFile('src/')->mkdirs();
        $project->getFile('resources/')->mkdirs();

        $project->getFile('resources/JPHP-INF')->mkdirs();

        $bootstrap = new FileStream($project->getPath('src/bootstrap.php'), 'w+');
        $bootstrap->write('<?php ');
        $bootstrap->close();

        $conf = new FileStream($project->getPath('resources/JPHP-INF/launcher.conf'), 'w+');
        $conf->write("env.debug = 0\n\n");
        $conf->write("env.extensions = spl, org.develnext.jphp.swing.SwingExtension\n\n");
        $conf->write("bootstrap.file = bootstrap.php\n\n");
        $conf->close();
    }

    function onUpdateProject(Project $project) {
        $this->updateBuildScript($project);
    }

    function onRenderFileInTree(ProjectFile $file) {
        $relPath = $file->getRelPath();
        $info = self::$specialPaths[$relPath];

        if ($info) {
            return $file->duplicate($info[0], $info[1]);
        }

        if (str::startsWith($relPath, '/resources/JPHP-INF'))
            return null;

        return $file;
    }


    protected function updateBuildScript(Project $project) {
        $out = new FileStream($project->getDirectory()->getPath() . '/build.gradle', 'w+');

        $version = "1.0";
        $name = $project->getName();

$out->write(<<<"DOC"
allprojects {
    apply plugin: 'java'

    group = '$name'
    version = '$version'

    repositories {
        maven { url 'http://maven.develnext.org/repository/snapshots/' }
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
