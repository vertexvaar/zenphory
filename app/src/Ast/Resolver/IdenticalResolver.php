<?php
declare(strict_types=1);
namespace VerteXVaaR\Zenphory\Ast\Resolver;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use Symfony\Component\VarDumper\VarDumper;

class IdenticalResolver extends NodeVisitorAbstract
{
    protected $replacements = [];

    public function enterNode(Node $node)
    {
        if ($node instanceof Node\Expr\BinaryOp\Identical) {
            VarDumper::dump($node);
            die;
            if ($node->left instanceof Node\Expr\ConstFetch && $node->right instanceof Node\Expr\ConstFetch) {
                if ($node->left->name->toString() === $node->right->name->toString()) {
                    $this->replacements[spl_object_hash($node)] = $node;
                }
            }
        }
    }

    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\Expr\BinaryOp\Identical) {
            if ($node->left instanceof Node\Expr\ConstFetch && $node->right instanceof Node\Expr\ConstFetch) {
                if ($node->left->name->toString() === $node->right->name->toString()) {
                    return NodeTraverser::REMOVE_NODE;
                }
            }
        }
    }
}
