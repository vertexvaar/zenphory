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
                } else {
                    VarDumper::dump($node->expr);
                    throw new \Exception('Implement case for this node type');
                }
                $this->variableBag->add($name, $value);
            }
        }
    }
}
