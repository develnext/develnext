<?php

namespace develnext\ide\components;

use develnext\ide\ImageManager;
use develnext\Manager;
use php\io\File;
use php\swing\event\MouseEvent;
use php\swing\Font;
use php\swing\tree\TreeNode;
use php\swing\UIDialog;
use php\swing\UIFileChooser;
use php\swing\UILabel;
use php\swing\UITree;

class __File {
    /** @var \php\io\File */
    protected $file;

    function __construct(File $file) {
        $this->file = $file;
    }

    function __toString() {
        return $this->file->getName() ? $this->file->getName() : $this->file->getPath();
    }

    /**
     * @return \php\io\File
     */
    public function getFile() {
        return $this->file;
    }
}

/**
 * Class UIDirectoryChooser
 * @package develnext\ide\components
 */
class UIDirectoryChooser {
    /** @var \develnext\IDEForm */
    protected $form;

    /** @var callable */
    protected $onSelected;

    /** @var callable */
    protected $onFetchIcon;

    /** @var File */
    protected $selectedFile;

    public function __construct() {
        $manager = Manager::getInstance();
        $this->form = $manager
            ->getSystemForm('ide/components/DirectoryChooser.xml', false);

        /** @var UITree $tree */
        $tree = $this->form->get('file-tree');

        $this->form->get('btn-cancel')->on('click', function(MouseEvent $e) {
            if ($e->target->enabled)
                $this->form->hide();
        });

        $this->form->get('btn-ok')->on('click', function(MouseEvent $e) use ($tree) {
            if ($e->target->enabled) {
                if ($tree->selectedNode && $tree->selectedNode->userData instanceof __File) {
                    $this->selectedFile = $tree->selectedNode->userData->getFile();
                }
                $this->form->hide($this->selectedFile);
            }
        });

        $this->form->get('btn-update')->on('click', function() use ($tree) {
            foreach ($tree->selectedNodes as $node) {
                $this->updateSubTree($node, $node->userData instanceof __File ? $node->userData->getFile() : null);
                $tree->model->nodeStructureChanged($node);
            }
        });

        $this->form->get('btn-folder-add')->on('click', function() use ($tree) {
            $selected = $tree->selectedNode;

            if ($selected->userData instanceof __File) {
                $name = UIDialog::input(_('Enter a directory name'));
                if ($name) {
                    $newDir = new File($selected->userData->getFile(), $name);
                    $newDir->mkdirs();

                    $this->updateSubTree($selected, $selected->userData->getFile());
                    $tree->model->nodeStructureChanged($selected);
                    $tree->expandNode($selected);

                    $tree->selectedNodes = [];
                    for($i = 0; $i < $selected->getChildCount(); $i++) {
                        $el = $selected->getChild($i);
                        if ((string)$el->userData == $name) {
                            $tree->addSelectionNode($el);
                            break;
                        }
                    }
                }
            }
        });

        $tree->onCellRender(function(TreeNode $node, UILabel $template) use ($tree) {
            $template->font = Font::decode('Tahoma 11');

            if ($node->userData instanceof __File) {
                $icon = null;
                if ($this->onFetchIcon)
                   $icon = call_user_func($this->onFetchIcon, $this, $node->userData->getFile());

                $template->setIcon($icon ? $icon : ImageManager::get('images/icons/filetype/folder.png'));
            }
        });

        $tree->on('expanded', function(UITree $tree, TreeNode $node) {
            if ($node->getChildCount() == 1 && $node->userData instanceof __File) {
                $child = $node->getChild(0);
                if (!$child->userData) {
                    $child->userData = '...';
                    $this->updateSubTree($node, $node->userData->getFile());
                }
            }
        });

        $tree->on('selected', function(UITree $tree, TreeNode $node = null) {
            if ($node && $node->userData instanceof __File) {
                $this->form->get('file-path')->text = $node->userData->getFile()->getPath();

                if ($this->onSelected) {
                    call_user_func($this->onSelected, $this, $node->userData->getFile());
                }
            }
        });
    }

    public function updateSubTree(TreeNode $node, File $file = null, $level = 0) {
        $files = $file == null ? File::listRoots() : $file->findFiles(function(File $dir, $name) {
            $file = new File($dir, $name);
            return $file->isDirectory() && !$file->isHidden();
        });

        if ($level > 0) {
            $node->removeAllChildren();

            if ($files)
                $node->add(new TreeNode(null));
            return;
        }

        $node->removeAllChildren();
        foreach($files as $root) {
            $item = new TreeNode();
            $item->userData = new __File($root);
            $node->add($item);

            $this->updateSubTree($item, $root, $level + 1);
        }
    }

    protected function updateTree() {
        /** @var UITree $tree */
        $tree = $this->form->get('file-tree');
        $tree->root->removeAllChildren();

        $this->updateSubTree($tree->root);
        $tree->expandNode($tree->root);

        $tree->grabFocus();
    }

    public function showDialog() {
        $this->selectedFile = null;
        $this->updateTree();
        $this->form->showModal();
    }

    public function onSelected(callable $handler = null) {
        $this->onSelected = $handler;
    }

    public function onFetchIcon(callable $handler = null) {
        $this->onFetchIcon = $handler;
    }

    public function setOkButtonEnabled($value) {
        $this->form->get('btn-ok')->enabled = $value;
    }

    /**
     * @return \php\io\File|null
     */
    public function getSelectedFile() {
        return $this->selectedFile;
    }
}
