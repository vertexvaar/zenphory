<?php
declare(strict_types=1);
namespace VerteXVaaR\Zenphory\Ast\Interpolator;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use VerteXVaaR\Zenphory\Bag\VariableBag;

class VariableInterpolator extends NodeVisitorAbstract
{
    /**
     * @var VariableBag
     */
    protected $variableBag = null;

    protected $assignmentLevel = 0;

    protected $safeToReplace = true;

    /**
     * VariableInterpolator constructor.
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
            $this->assignmentLevel++;
        } elseif ($node instanceof Node\Expr\Variable
            && $this->assignmentLevel === 0
            && $this->safeToReplace
            && $this->variableBag->has($node->name)
        ) {
            return $this->variableBag->get($node->name);
        }
    }

    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\Expr\Assign) {
            $this->assignmentLevel--;
        }
    }
}
