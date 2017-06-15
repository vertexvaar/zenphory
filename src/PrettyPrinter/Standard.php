<?php
declare(strict_types=1);
namespace VerteXVaaR\Zenphory\PrettyPrinter;

use VerteXVaaR\Zenphory\Ast\Node\Multinode;

class Standard extends \PhpParser\PrettyPrinter\Standard
{
    protected function pMultinode(Multinode $multinode)
    {
        return $return[] = $this->pStmts($multinode->nodes, false);
    }
}
