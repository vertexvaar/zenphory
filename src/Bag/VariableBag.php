<?php
declare(strict_types=1);
namespace VerteXVaaR\Zenphory\Bag;

use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use Symfony\Component\VarDumper\VarDumper;

class VariableBag
{
    protected $blacklist = [];

    protected $variables = [];

    public function add($name, $value)
    {
        if (isset($this->blacklist[$name])) {
            return;
        } elseif (isset($this->variables[$name])) {
            $this->blacklist[$name] = true;
            unset($this->variables[$name]);
            return;
        } elseif ($value instanceof ConstFetch) {
        } else {
            switch (gettype($value)) {
                case 'string':
                    $value = new String_($value);
                    break;
                case 'integer':
                    $value = new LNumber($value);
                    break;
                case 'array':
                    $value = new Array_($value);
                    break;
                default:
                    VarDumper::dump([gettype($value) => $value]);
                    throw new \Exception('Type missing');
            }
        }
        $this->variables[$name] = $value;
    }

    public function has($name)
    {
        return isset($this->variables[$name]);
    }

    public function get($name)
    {
        return $this->variables[$name];
    }
}
