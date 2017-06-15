<?php
declare(strict_types=1);
namespace VerteXVaaR\Zenphory\Ast\Concatenator;

use PhpParser\Node;
use PhpParser\NodeAbstract;
use PhpParser\NodeVisitorAbstract;
use Symfony\Component\VarDumper\VarDumper;

class ValueConcatenation extends NodeVisitorAbstract
{
    protected $changed = false;

    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\Expr\BinaryOp\Concat) {
            $stack = $this->stackNodes($node);
            $stack = $this->concatNodes($stack);
            $node = $this->concatStack($stack);
        }
        return $node;
    }

    /**
     * @param Node $node
     * @param array $stack
     *
     * @return array
     */
    protected function stackNodes(Node $node, array $stack = [])
    {
        if ($node instanceof Node\Expr\BinaryOp\Concat) {
            $stack = $this->stackNodes($node->left, $stack);
            $stack = $this->stackNodes($node->right, $stack);
        } else {
            $stack[] = $node;
        }
        return $stack;
    }

    protected function concatNodes(array $stack)
    {
        $limit = count($stack) - 1;
        for ($i = 0; $i < $limit; $i++) {
            if ($this->isScalarWithValue($stack[$i])) {
                if ($this->isScalarWithValue($stack[$i + 1])) {
                    $node = new Node\Scalar\String_($stack[$i]->value . $stack[$i + 1]->value);
                    $stack[$i] = $node;
                    unset($stack[$i + 1]);
                    $i++;
                } else {
                    $i++;
                }
            }
        }
        return $stack;
    }

    protected function isScalarWithValue(Node $node)
    {
        return $node instanceof Node\Scalar\String_
            || $node instanceof Node\Scalar\LNumber;
    }

    protected function concatStack($stack)
    {
        VarDumper::dump($stack);
        $initial = array_shift($stack);
        $node = array_reduce($stack, [$this, 'reduceStack'], $initial);
        return $node;
    }

    protected function reduceStack($carry, $item)
    {
        if ($carry instanceof Node\Expr\BinaryOp\Concat) {
            $carry->right = new Node\Expr\BinaryOp\Concat($carry->right, $item);
        } else {
            $carry = new Node\Expr\BinaryOp\Concat($carry, $item);
        }
        return $carry;
    }

    public function flushChanged()
    {
        try {
            return $this->changed;
        } finally {
            $this->changed = false;
        }
    }
}
