<?php
declare(strict_types=1);
namespace VerteXVaaR\Zenphory\Ast\Concatenator;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

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
               || $node instanceof Node\Scalar\LNumber
               || $node instanceof Node\Scalar\DNumber;
    }

    protected function concatStack($stack)
    {
        $nodeStack = [];
        while (count($stack) > 1) {
            $left = array_shift($stack);
            $right = array_shift($stack);
            $node = new Node\Expr\BinaryOp\Concat($left, $right);
            $nodeStack[] = $node;
        }
        if (1 === count($stack)) {
            $lastNode = end($nodeStack);
            $index = key($nodeStack);
            $nodeStack[$index] = new Node\Expr\BinaryOp\Concat($lastNode, array_shift($stack));
        }

        while (count($nodeStack) > 1) {
            $nodeStack[] = new Node\Expr\BinaryOp\Concat(array_shift($nodeStack), array_shift($nodeStack));
        }
        return $nodeStack[0];
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
