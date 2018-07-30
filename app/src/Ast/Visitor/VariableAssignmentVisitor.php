<?php
declare(strict_types=1);
namespace VerteXVaaR\Zenphory\Ast\Visitor;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use Symfony\Component\VarDumper\VarDumper;
use VerteXVaaR\Zenphory\Bag\VariableBag;

class VariableAssignmentVisitor extends NodeVisitorAbstract
{
    /**
     * @var VariableBag
     */
    protected $variableBag = null;

    /**
     * VariableAssignmentVisitor constructor.
     *
     * @param VariableBag $variableBag
     */
    public function __construct(VariableBag $variableBag)
    {
        $this->variableBag = $variableBag;
    }

    public function enterNode(Node $node)
    {
        if ($node instanceof Node\Expr\Assign) {
            if ($node->var instanceof Node\Expr\Variable) {
                $name = $node->var->name;
                if ($node->expr instanceof Node\Scalar\String_) {
                    $value = $node->expr->value;
                } elseif ($node->expr instanceof Node\Scalar\LNumber) {
                    $value = $node->expr->value;
                } elseif ($node->expr instanceof Node\Expr\ConstFetch) {
                    $value = $node->expr;
                } elseif ($node->expr instanceof Node\Expr\Array_) {
                    $value = $node->expr->items;
//                } elseif ($node->expr instanceof Node\Expr\ArrayDimFetch) {
//                    $value = null;
//                    if ($this->variableBag->has($node->expr->var->name)) {
//                        $array = $this->variableBag->get($node->expr->var->name)->items;
//                        $key = $node->expr->dim->value;
//                        if (array_key_exists($key, $array)) {
//                            $value = $array[$key]->value->value;
//                        }
//                    }
                } else {
                    VarDumper::dump($node->expr);
                    throw new \Exception('Implement case for this node type');
                }
                $this->variableBag->add($name, $value);
            }
        }
    }
}
