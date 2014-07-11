<?php
namespace develnext\project;

use develnext\lang\Singleton;
use php\io\File;
use php\io\Stream;
use php\lib\str;
use php\swing\event\MouseEvent;
use php\swing\Font;
use php\swing\Image;
use php\swing\tree\TreeNode;
use php\swing\UILabel;
use php\swing\UITree;
use php\util\Flow;

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

    /** @var ProjectFile */
    protected $cachedNodes = [];

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
        $tree->expandsSelectedPaths = true;
        $tree->rowHeight = 22;
        $tree->dragEnabled = true;
        $tree->font = new Font('Tahoma', 0, 11);

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
    }

    public function getTree() {
        if ($this->tree == null)
            throw new \Exception("Tree is not set");

        return $this->tree;
    }

    /**
     * @param \php\swing\tree\TreeNode $parent
     * @param ProjectFile $file
     * @return TreeNode
     */
    protected function findNode(TreeNode $parent, ProjectFile $file) {
        if ($r = $this->cachedNodes[$file->hashCode()])
            return $r;

        for($i = 0; $i < $parent->getChildCount(); $i++) {
            $child = $parent->getChild($i);
            if ($child->userData instanceof ProjectFile) {
                if ($child->userData->hashCode() === $file->hashCode()) {
                    return $this->cachedNodes[$file->hashCode()] = $child;
                } else {
                    if ($child->getChildCount() > 0) {
                        $r = $this->findNode($child, $file);
                        if ($r)
                            return $this->cachedNodes[$file->hashCode()] = $r;
                    }
                }
            }
        }
        return null;
    }

    /**
     * @param ProjectFile $file
     * @return null|TreeNode
     */
    protected function createNode(ProjectFile $file) {
        if ($this->project->getFileMark($file, 'hidden'))
            return null;

        $projectFile = $this->project->getType()->onRenderFileInTree($file);
        if ($projectFile == null)
            return null;

        $item = new TreeNode($projectFile, $projectFile->getFile()->isDirectory());
        return $item;
    }

    protected function updateNode(TreeNode $node, array $files, $clear = true) {
        if ($clear)
            $node->removeAllChildren();

        foreach ($files as $el) {
            /** @var File $file */
            $file = $el[0];

            $projectFile = new ProjectFile($file, $this->project);
            if ($this->project->getFileMark($projectFile, 'hidden'))
                continue;

            $projectFile = $this->project->getType()->onRenderFileInTree($projectFile);
            if ($projectFile == null)
                continue;

            $item = new TreeNode($projectFile, $file->isDirectory());
            $node->add($item);

            if ($el[1])
                $this->updateNode($item, $el[1]);
        }
    }

    public function updateAll(ProjectFile $parent = null) {
        $tree = $this->getTree();
        $contentRoots = [];

        if ($parent == null) {
            $tree->root->removeAllChildren();

            $contentRoots = $this->project->getContentRoots();

            $parent = new ProjectFile($this->project->getDirectory(), $this->project);
            $root   = $tree->root;
        } else {
            $root = $this->findNode($tree->root, $parent);
            if ($root == null) {
                $root = $this->createNode($parent);
                if ($root == null)
                    return;

                $tree->root->add($root);
                $tree->model->nodeStructureChanged($tree->root);
            }
        }

        $files = $this->scanner->getSubTree($parent->getFile());
        $tree->root->userData = $this->project->getName();

        $this->updateNode($root, $files);
        foreach ($contentRoots as $el) {
            $this->updateAll($el);
        }

        $tree->model->reload($root);
    }

    public function updateFile(ProjectFile $file) {
        if ($node = $this->findNode($this->getTree()->root, $file)) {
            $node->removeFromParent();
            unset($this->cachedNodes[ $file->hashCode() ]);
        }

        do {
            if ($file->getParent() == null)
                return;

            $node = $this->findNode($this->getTree()->root, $file->getParent());
            if ($node == null) {
                $this->updateFile($file->getParent());
            }
        } while ($node == null);

        $item = $this->createNode($file);
        if ($file->getFile()->exists()) {
            if ($item) {
                $idx = null;
                for($i = 0; $i < $node->getChildCount(); $i++) {
                    $child = $node->getChild($i);
                    if ($child->userData instanceof ProjectFile) {
                        $dir = $child->userData->getFile()->isDirectory();
                        if ($dir && !$file->getFile()->isDirectory())
                            continue;

                        if (!$dir && $file->getFile()->isDirectory()) {
                            $idx = $i;
                            break;
                        }

                        if (str::compare($file->getFile()->getName(), $child->userData->getFile()->getName()) < 0) {
                            $idx = $i;
                            break;
                        }
                    }
                }

                if ($idx !== null)
                    $node->insert($idx, $item);
                else
                    $node->add($item);
            }
        }

        if ($node)
            $this->getTree()->model->nodeStructureChanged($node);
    }

    public function close() {
        if ($this->tree) {
            $this->tree->root->removeAllChildren();
            $this->tree->model->reload($this->tree->root);
        }
    }

    public function getCurrentFile() {
        $data = $this->getTree()->selectedNode->userData;
        if ($data instanceof ProjectFile)
            return $data;

        return new ProjectFile($this->project->getDirectory(), $this->project);
    }

    /**
     * @return ProjectFile[]
     */
    public function getSelectedFiles() {
        $result = [];
        foreach($this->getTree()->selectedNodes as $node) {
            if ($node->userData instanceof ProjectFile) {
                $result[] = $node->userData;
            }
        }
        return $result;
    }

    public function selectFile(ProjectFile $file) {
        if ($this->tree) {
            $node = $this->findNode($this->tree->root, $file);
            if ($node) {
                $parent = $node->parent;
                while ($parent != null) {
                    if (!$this->tree->isExpandedNode($parent)) {
                        $this->tree->expandNode($parent);
                        $this->tree->expandNodeAll($parent);
                    }

                    $parent = $parent->parent;
                }

                $this->tree->selectedNodes = [];
                $this->tree->addSelectionNode($node);
            }
        }
    }
}
