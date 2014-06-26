<?php
namespace develnext\project;

use develnext\lang\Singleton;
use php\io\File;
use php\io\Stream;
use php\swing\event\MouseEvent;
use php\swing\Image;
use php\swing\tree\TreeNode;
use php\swing\UILabel;
use php\swing\UITree;

/**
 * Class FileTreeManager
 * @package develnext\project
 */
class FileTreeManager {
    /** @var UITree */
    protected $tree;

    /** @var ProjectFileScanner */
    protected $scanner;

    /** @var Project */
    protected $project;

    public function __construct(Project $project) {
        $this->project = $project;
        $this->scanner = new ProjectFileScanner($project);
    }

    public function setEditorManager(EditorManager $manager) {
        $this->getTree()->on('click', function(MouseEvent $e) use ($manager) {
            $tree = $this->getTree();

            if ($e->clickCount > 1) {
                /** @var ProjectFile $projectFile */
                $projectFile = $tree->selectedNode->userData;

                if ($projectFile instanceof ProjectFile) {
                    $manager->open($projectFile);
                }
            }
        });
    }

    public function setTree(UITree $tree) {
        $this->tree = $tree;
    }

    public function getTree() {
        if ($this->tree == null)
            throw new \Exception("Tree is not set");

        return $this->tree;
    }

    protected function updateNode(TreeNode $node, array $files) {
        $node->removeAllChildren();
        foreach ($files as $el) {
            /** @var File $file */
            $file = $el[0];

            $projectFile = $this->project->getType()->onRenderFileInTree(new ProjectFile($file, $this->project));
            if ($projectFile == null)
                continue;

            $item = new TreeNode($projectFile, $file->isDirectory());
            $node->add($item);

            if ($el[1])
                $this->updateNode($item, $el[1]);
        }
    }

    public function updateAll() {
        $tree = $this->getTree();
        $projectType = $this->project->getType();

        $tree->onCellRender(function(TreeNode $node, UILabel $template) use ($projectType, $tree) {
            /** @var ProjectFile $file */
            $file = $node->userData;

            if ($file instanceof ProjectFile) {
                $template->setIcon($file->getIcon());
            }

            return $template;
        });

        $files = $this->scanner->getTree();

        $tree->root->userData = $this->project->getName();
        $this->updateNode($tree->root, $files);
        $tree->model->reload($tree->root);
    }

    public function close() {
        if ($this->tree) {
            $this->tree->root->removeAllChildren();
            $this->tree->model->reload($this->tree->root);
        }
    }
}
