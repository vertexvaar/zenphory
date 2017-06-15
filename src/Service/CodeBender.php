<?php
declare(strict_types=1);
namespace VerteXVaaR\Zenphory\Service;

use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use Symfony\Component\VarDumper\VarDumper;
use VerteXVaaR\Zenphory\Ast\Concatenator\ValueConcatenation;
use VerteXVaaR\Zenphory\Ast\Interpolator\VariableInterpolator;
use VerteXVaaR\Zenphory\Ast\Resolver\IfResolver;
use VerteXVaaR\Zenphory\Ast\Visitor\VariableAssignmentVisitor;
use VerteXVaaR\Zenphory\Bag\VariableBag;
use VerteXVaaR\Zenphory\PrettyPrinter\Standard;

class CodeBender
{
    /**
     * @param string $code
     *
     * @return string
     */
    public function process(string $code)
    {
        $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        $ast = $parser->parse($code);

        $variableBag = new VariableBag();
        $variableAssignmentVisitor = new VariableAssignmentVisitor($variableBag);
        $variableInterpolator = new VariableInterpolator($variableBag);

        // Collect variables and replace them with their values
        $traverser = new NodeTraverser();
        $traverser->addVisitor($variableAssignmentVisitor);
        $traverser->addVisitor($variableInterpolator);

        $ast = $traverser->traverse($ast);

        // Concatenate strings
        $valueConcatenation = new ValueConcatenation();
        $ifResolver = new IfResolver();

        $traverser = new NodeTraverser();
        $traverser->addVisitor($valueConcatenation);
        $traverser->addVisitor($ifResolver);

        do {
            $ast = $traverser->traverse($ast);
        } while($valueConcatenation->flushChanged());

        $prettyPrinter = new Standard();
        $code = $prettyPrinter->prettyPrintFile($ast) . PHP_EOL;

        VarDumper::dump($variableBag);

        return $code;
    }
}
