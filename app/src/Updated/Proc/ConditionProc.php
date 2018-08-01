<?php
declare(strict_types=1);

namespace VerteXVaaR\Zenphory\Updated\Proc;

use PhpParser\Node;
use VerteXVaaR\Zenphory\Updated\Proc\Condition\IfCondition;
use VerteXVaaR\Zenphory\Updated\Proc\Condition\IfElseCondition;
use VerteXVaaR\Zenphory\Updated\Proc\Condition\IfElseifCondition;
use VerteXVaaR\Zenphory\Updated\Proc\Condition\IfElseifElseCondition;

/**
 * Class ConditionProc
 */
class ConditionProc
{
    const ELSE = 0b01;
    const ELSEIFS = 0b10;

    protected $map = [
        0b0 => IfCondition::class,
        self::ELSE => IfElseCondition::class,
        self::ELSEIFS => IfElseifCondition::class,
        self::ELSE + self::ELSEIFS => IfElseifElseCondition::class
    ];

    /**
     * @param Node\Stmt\If_ $node
     * @return mixed
     */
    public function exec(Node\Stmt\If_ $node)
    {
        $type = 0b0;
        if (null !== $node->else) {
            $type += self::ELSE;
        }
        if (!empty($node->elseifs)) {
            $type += self::ELSEIFS;
        }

        $class = $this->map[$type];

        return new $class();
    }
}
