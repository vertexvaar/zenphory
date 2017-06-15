<?php
declare(strict_types=1);
namespace VerteXVaaR\Zenphory\Ast\Node;

use PhpParser\NodeAbstract;

class Multinode extends NodeAbstract
{
    public $nodes = [];

    /**
     * Multinode constructor.
     *
     * @param array $nodes
     */
    public function __construct(array $nodes, array $attributes = [])
    {
        parent::__construct($attributes);
        $this->nodes = $nodes;
    }

    public function getType()
    {
        return 'Multinode';
    }

    public function getSubNodeNames()
    {
        return ['nodes'];
    }
}
