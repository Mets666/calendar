<?php

namespace AppBundle\DQL;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;

/**
 * TimeDiffFunction ::= "TIMEDIFF" "(" ArithmeticPrimary "," ArithmeticPrimary ")"
 */
class TimeDiff extends FunctionNode
{
    /**
     * @var string
     */
    public $dateTime1;

    /**
     * @var string
     */
    public $dateTime2;

    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->dateTime1 = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->dateTime2 = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return 'TIME_TO_SEC(TIMEDIFF(' .
        $this->dateTime1->dispatch($sqlWalker) . ', ' .
        $this->dateTime2->dispatch($sqlWalker) .
        ')) / 60';
    }
}