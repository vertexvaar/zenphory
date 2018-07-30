<?php
declare(strict_types=1);
namespace VerteXVaaR\Zenphory\Ast\Resolver;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use VerteXVaaR\Zenphory\Ast\Node\Multinode;

class IfResolver extends NodeVisitorAbstract
{
    public function enterNode(Node $node)
    {
        if ($node instanceof Node\Stmt\If_) {
            if ($node->cond instanceof Node\Expr\BinaryOp) {
                if ($node->cond->left instanceof Node\Expr\ConstFetch
                    && $node->cond->right instanceof Node\Expr\ConstFetch
                ) {
                    if ($node->cond->left->name->toString() === $node->cond->right->name->toString()) {
                        return new Multinode($node->stmts);
                    }
                }
            }
        }
    }
}
