<?php
declare(strict_types=1);
namespace VerteXVaaR\Zenphory\Updated;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use VerteXVaaR\Zenphory\Updated\Proc\ConditionProc;

class Proccer extends NodeVisitorAbstract
{
    /**
     * @var Scope[]
     */
    protected $scope = [];

    public function enterNode(Node $node)
    {
        switch (get_class($node)) {
            case Node\Stmt\If_::class:
                $proc = new ConditionProc();
                $type = $proc->exec($node);
            default:
                echo '[NODE] ' . get_class($node) . ' not implemented <br>';
        }
    }

    public function leaveNode(Node $node)
    {
        switch (get_class($node)) {
            default:
                echo '[NODE] ' . get_class($node) . ' not implemented <br>';
        }
    }
}
