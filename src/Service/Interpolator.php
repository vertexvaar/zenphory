<?php
declare(strict_types=1);
namespace VerteXVaaR\Zenphory\Service;

use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Declare_;
use PhpParser\NodeAbstract;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;
use Symfony\Component\VarDumper\VarDumper;

class Interpolator
{
    /**
     * @var string
     */
    protected $source = '';

    /**
     * @var string
     */
    protected $target = '';

    /**
     * Interpolator constructor.
     *
     * @param string $source
     * @param string $target
     */
    public function __construct($source, $target)
    {
        $this->source = rtrim($source, '/') . '/';
        $this->target = rtrim($target, '/') . '/';
    }

    public function run()
    {
        $files = ['variables.php'];
        foreach ($files as $file) {
            $this->process($this->source . $file, $this->target . $file);
        }
    }

    protected function process($file, $target)
    {
        $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        $ast = $parser->parse(file_get_contents($file));
        $variables = $this->gatherVariables($ast);
        $this->interpolate($ast, $variables);
        $prettyPrinter = new Standard();
        $conten = $prettyPrinter->prettyPrintFile($ast);
        file_put_contents($target, $conten);
    }

    /**
     * @param NodeAbstract[] $ast
     * @param array $variables
     *
     * @return array
     * @throws \Exception
     */
    protected function gatherVariables($ast, $variables = [])
    {
        foreach ($ast as $item) {
            if ($item instanceof Declare_) {
            } elseif ($item instanceof BinaryOp\Concat) {
                $variables = $this->gatherVariables([$item->left], $variables);
                $variables = $this->gatherVariables([$item->right], $variables);
            } elseif ($item instanceof Assign) {
                $name = $this->extractName($item->var);
                $value = $this->extractValue($item->expr);
                if (isset($variables[$name])) {
                    throw new \Exception('Duplicate variable declaration');
                }
                $variables[$name] = $value;
            } else {
                $subNodeNames = $item->getSubNodeNames();
                if (!is_array($subNodeNames)) {
                    VarDumper::dump($subNodeNames);
                    throw new \Exception('Has no subnodes, aye');
                }
                foreach ($subNodeNames as $name) {
                    $subAst = $item->{$name};
                    if (false) {
                    } elseif (null === $subAst) {
                    } elseif (is_string($subAst)) {
                    } elseif ($subAst instanceof Name) {
                    } elseif ($subAst instanceof BinaryOp) {
                        $variables = $this->gatherVariables([$subAst->left], $variables);
                        $variables = $this->gatherVariables([$subAst->right], $variables);
                    } elseif (!is_array($subAst)) {
                        VarDumper::dump([$subAst, $subNodeNames]);
                        throw new \Exception('SubAST is weird, yo');
                    } else {
                        $variables = $this->gatherVariables($subAst, $variables);
                    }
                }
            }
        }
        return $variables;
    }

    protected function extractName($var)
    {
        if ($var instanceof Variable) {
            return $var->name;
        } else {
            throw new \Exception('Can not determine variable name');
        }
    }

    protected function extractValue($expr)
    {
        if (is_string($expr) && 'true' === strtolower($expr)) {
            return true;
        } elseif (is_string($expr) && 'false' === strtolower($expr)) {
            return false;
        } elseif ($expr instanceof String_) {
            return $expr->value;
        } elseif ($expr instanceof LNumber) {
            return $expr->value;
        } elseif ($expr instanceof ConstFetch) {
            return $this->extractValue($expr->name->getFirst());
        } else {
            VarDumper::dump($expr);
            throw new \Exception('Can not determine variable value');
        }
    }

    /**
     * @param NodeAbstract[] $ast
     * @param array $variables
     *
     * @return array
     * @throws \Exception
     */
    protected function interpolate($ast, $variables)
    {
        if (!is_array($ast)) {
            VarDumper::dump($ast);
            throw new \Exception('AST is not an array, buaaah');
        }
        foreach ($ast as $index => $item) {
            if ($item instanceof Variable) {
                $name = $this->extractName($item);
                if (isset($variables[$name])) {
                    $value = $variables[$name];
                    switch (gettype($value)) {
                        case 'integer':
                            $newType = new LNumber($value);
                            break;
                        case 'string':
                            $newType = new String_($value);
                            break;
                        case 'boolean':
                            if ($value === true) {
                                $stringVal = 'true';
                            } else {
                                $stringVal = 'false';
                            }
                            $newType = new ConstFetch(new Name($stringVal));
                            break;
                        default:
                            VarDumper::dump([gettype($value), $value]);
                            throw new \Exception('Missing type returner');
                    }
                    $ast[$index] = $newType;
                }
            } elseif ($item instanceof Declare_) {
            } elseif ($item instanceof Name) {
            } elseif ($item instanceof ConstFetch) {
            } elseif ($item instanceof Assign) {
                if (!($item->var instanceof Variable)) {
                    VarDumper::dump($item->var);
                    throw new \Exception('Unexpected variable var');
                }
            } else {
                if (is_array($item)) {
                    foreach (array_keys($item) as $idx) {
                        $subItem = $item[$idx];
                        $alteredSubItem = $this->interpolate([$subItem], $variables)[0];
                        $item[$idx] = $alteredSubItem;
                    }
                } elseif (is_string($item)) {
                } elseif (null === $item) {
                } else {
                    foreach ($item->getSubNodeNames() as $name) {
                        $subAst = $item->{$name};
                        $alteredSubAst = $this->interpolate([$subAst], $variables)[0];
                        $item->{$name} = $alteredSubAst;
                    }
                }
            }
        }
        return $ast;
    }
}
