<?php

namespace develnext\ide\components;

use develnext\ide\ImageManager;
use develnext\IDEForm;
use develnext\Manager;
use php\io\File;
use php\lang\System;
use php\lang\Thread;
use php\lib\str;
use php\swing\event\MouseEvent;
use php\swing\Font;
use php\swing\SwingWorker;
use php\swing\tree\TreeNode;
use php\swing\UIDialog;
use php\swing\UIFileChooser;
use php\swing\UILabel;
use php\swing\UIMenuItem;
use php\swing\UITree;

use develnext\ide\components\UIDirectoryChooser_File as __File;
use develnext\ide\components\UIDirectoryChooser_UpdateSubTreeWorker as UpdateSubTreeWorker;

class UIDirectoryChooser_File {
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

    /** @var array */
    protected static $init;

    /** @var IDEForm */
    protected static $forms;

    public function __construct($cachedGroup = 'main') {
        $manager = Manager::getInstance();

        if (!($this->form = self::$forms[$cachedGroup])) {
            $this->form = $manager->getSystemForm('ide/components/DirectoryChooser.xml', false);
            self::$forms[$cachedGroup] = $this->form;
        } else {
            return;
        }

        /** @var UITree $tree */
        $tree = $this->form->get('file-tree');
        $tree->scrollsOnExpand = true;

        $tree->popupMenu = $this->form->get('popup-menu');

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

        $this->form->get('btn-update')->on('click', $handle = function() use ($tree) {
            foreach ($tree->selectedNodes as $node) {
                $this->updateSubTree(
                    $node,
                    $node->userData instanceof __File ? $node->userData->getFile() : null,
                    function() use ($tree, $node) {
                        $tree->model->nodeStructureChanged($node);
                    }
                );
            }
        });
        /** @var UIMenuItem $item */
        $item = $this->form->get('menu-update');
        $item->on('click', $handle);
        $item->accelerator = 'F5';

        $this->form->get('btn-folder-add')->on('click', $handle = function() use ($tree) {
            $selected = $tree->selectedNode;

            if ($selected && $selected->userData instanceof __File) {
                $name = UIDialog::input(_('ide.components.folder_add.text'));
                if ($name) {
                    $newDir = new File($selected->userData->getFile(), $name);
                    if ($newDir->exists()) {
                        UIMessages::error(_('ide.components.folder_add.exists'));
                        return;
                    }

                    if (!$newDir->mkdirs()) {
                        UIMessages::error(_('ide.components.folder_add.fail'));
                    }

                    $this->updateSubTree($selected, $selected->userData->getFile(),
                        function() use ($tree, $selected, $name) {

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
                    });
                }
            }
        });
        /** @var UIMenuItem $item */
        $item = $this->form->get('menu-folder-add');
        $item->on('click', $handle);
        $item->accelerator = 'alt INSERT';


        $this->form->get('btn-delete')->on('click', $handle = function() use ($tree) {
            $selected = $tree->selectedNode;

            if ($selected && $selected->userData instanceof __File) {
                if (UIDialog::confirm(
                        _('ide.components.delete.question', [$selected->userData->getFile()]),
                        _('ide.components.delete.title')) == UIDialog::YES_OPTION) {
                    /** @var File $file */
                    $file = $selected->userData->getFile();
                    if (!self::deleteFile($file)) {
                        UIMessages::error(_('ide.components.delete.fail'));
                    } else {
                        $this->findNode($file->getParentFile(), function(TreeNode $node) use ($tree, $file) {
                            $this->updateSubTree($node == null ? $tree->root : $node, $file->getParentFile(),
                                function() use ($tree, $node) {
                                    $tree->model->nodeStructureChanged($node);
                            });
                        });
                    }
                }
            }
        });
        /** @var UIMenuItem $item */
        $item = $this->form->get('menu-delete');
        $item->on('click', $handle);
        $item->accelerator = 'DELETE';

        $tree->onCellRender(function(TreeNode $node, UILabel $template) use ($tree) {
            $template->font = Font::decode('Tahoma 11');

            if ($node->userData instanceof __File) {
                $icon = null;
                if ($this->onFetchIcon)
                   $icon = call_user_func($this->onFetchIcon, $this, $node->userData->getFile());

                $template->setIcon($icon ? $icon : ImageManager::get('images/icons/filetype/folder.png'));
            }
        });

        $tree->on('expanded', function(UITree $self, TreeNode $node) use ($tree) {
            if ($node->getChildCount() == 1 && $node->userData instanceof __File) {
                $child = $node->getChild(0);
                if (!$child->userData) {
                    $child->userData = '... ' . _('ide.components.loading');
                    $this->updateSubTree($node, $node->userData->getFile(), function() use ($node, $tree) {
                        $tree->model->nodeStructureChanged($node);
                    });
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

        $this->form->get('btn-home')->on('click', $handle = function(){
            $home = System::getProperty('user.home');
            if (!$home)
                $home = System::getProperty('user.dir');

            $this->setSelectedFile(new File($home));
        });
        /** @var UIMenuItem $item */
        $item = $this->form->get('menu-home');
        $item->on('click', $handle);
        $item->accelerator = 'alt HOME';

        $this->updateTree();
        /*$this->form->getWindow()->on('windowActive', function(){
            $this->form->get('btn-update')->trigger('click');
        });*/
    }

    public function updateSubTree(TreeNode $node, File $file = null, callable $callback = null) {
        $worker = new UpdateSubTreeWorker($node, $file, $callback);
        $worker->execute();
    }

    protected function updateTree() {
        /** @var UITree $tree */
        $tree = $this->form->get('file-tree');
        $tree->root->removeAllChildren();

        $this->updateSubTree($tree->root, null, function() use ($tree) {
            $tree->expandNode($tree->root);
        });

        $tree->grabFocus();
    }

    public function showDialog() {
        $this->selectedFile = null;
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

    public function setTitle($title) {
        $this->form->getWindow()->title = $title;
    }

    public function getTitle() {
        return $this->form->getWindow()->title;
    }

    protected function findNode(File $file, callable $callback) {
        $path = $file->getAbsolutePath();

        if (!$file->getName())
            $path = [$path];
        else
            $path = str::split($path, File::DIRECTORY_SEPARATOR);

        /** @var UITree $tree */
        $tree = $this->form->get('file-tree');

        $fn = function(TreeNode $node, $offset) use ($tree, $path, &$fn, $callback) {
            if ($node->getChildCount() == 1 && !$node->getChild(0)->userData)
                $this->updateSubTree($node, $node->userData instanceof __File ? $node->userData->getFile() : null,
                    function() use ($node, $offset, $fn) {
                        $fn($node, $offset);
                    });
            else {
                $name = $path[$offset];

                for($i = 0; $i < $node->getChildCount(); $i++) {
                    $child = $node->getChild($i);
                    if ($child->userData instanceof __File
                        && (string)$child->userData == $name) {

                        if ($offset + 1 >= sizeof($path)) {
                            $callback($child);
                            break;
                        }

                        $fn($child, $offset + 1);
                    }
                }
            }
        };

        $fn($tree->root, 0);
    }

    public function setSelectedFile(File $file) {
        $path = str::replace($file->getAbsolutePath(), '\\', '/');
        $path = str::split($path, '/');

        /** @var UITree $tree */
        $tree = $this->form->get('file-tree');

        $fn = function(TreeNode $node, $offset) use ($tree, $path, &$fn) {
            if ($node->getChildCount() == 1 && !$node->getChild(0)->userData)
                $this->updateSubTree($node, $node->userData instanceof __File ? $node->userData->getFile() : null,
                    function() use ($node, $offset, $fn) {
                       $fn($node, $offset);
                });
            else {
                $tree->expandNode($node);

                $name = (new File($path[$offset]))->getName();
                for($i = 0; $i < $node->getChildCount(); $i++) {
                    $child = $node->getChild($i);
                    if ($child->userData instanceof __File
                        && $child->userData->getFile()->getName() == $name) {

                        if ($offset + 1 >= sizeof($path)) {
                            $tree->clearSelection();
                            $tree->addSelectionNode($child);
                            $tree->scrollToNode($child);
                            $tree->expandNode($child);
                            break;
                        }

                        $fn($child, $offset + 1);
                    }
                }
            }
        };

        $fn($tree->root, 0);
    }

    /**
     * @return \php\io\File|null
     */
    public function getSelectedFile() {
        return $this->selectedFile;
    }

    public static function deleteFile(File $file) {
        $r = true;
        if ($file->isDirectory()) {
            foreach($file->findFiles() as $el) {
                $r = $r && self::deleteFile($el);
            }
        }

        return $r && $file->delete();
    }
}

class UIDirectoryChooser_UpdateSubTreeWorker extends SwingWorker {
    /** @var TreeNode */
    protected $node;

    /** @var File */
    protected $file;

    /** @var callable */
    protected $callback;

    function __construct(TreeNode $node, File $file = null, callable $callback = null) {
        $this->node = $node;
        $this->file = $file;
        $this->callback = $callback;
    }

    public function update(TreeNode $node, File $file = null, $level = 0) {
        $files = $file == null ? File::listRoots() : $file->findFiles(function(File $dir, $name) {
            $file = new File($dir, $name);
            return $file->isDirectory() && !$file->isHidden();
        });

        if ($level > 0) {
            $this->publish([[$node]]);
            //$node->removeAllChildren();

            if ($files) {
                $this->publish([[$node, new TreeNode(null)]]);
                //$node->add(new TreeNode(null));
            }
            return;
        }

        $this->publish([[$node]]);
        //$node->removeAllChildren();
        foreach($files as $root) {
            $item = new TreeNode();
            $item->userData = new __File($root);

            $this->publish([[$node, $item]]);
            //$node->add($item);

            $this->update($item, $root, $level + 1);
        }

        $this->publish([null]);
    }

    /**
     * @return mixed
     */
    protected function doInBackground() {
        $this->update($this->node, $this->file);
    }

    protected function process(array $values) {
        foreach ($values as $value) {
            if (!$value && $this->callback) {
                call_user_func($this->callback);
            }

            if ($value) {
                /** @var TreeNode $node */
                $node = $value[0];

                /** @var TreeNode $item */
                $item = $value[1];

                if ($item)
                    $node->add($item);
                else
                    $node->removeAllChildren();
            }
        }
    }
}
