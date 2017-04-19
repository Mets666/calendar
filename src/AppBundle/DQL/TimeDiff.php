<?php

namespace AppBundle\DQL;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * TimeDiffFunction ::= "TIMEDIFF" "(" ArithmeticPrimary "," ArithmeticPrimary ")"
 */
class TimeDiff extends FunctionNode
{

    public $dateTime1;
    
    public $dateTime2;

    /**
     * @param Parser $parser
     */
    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->dateTime1 = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->dateTime2 = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    /**
     * @param SqlWalker $sqlWalker
     * @return string
     */
    public function getSql(SqlWalker $sqlWalker)
    {
        return 'TIME_TO_SEC(TIMEDIFF(' .
        $this->dateTime1->dispatch($sqlWalker) . ', ' .
        $this->dateTime2->dispatch($sqlWalker) .
        ')) / 60';
    }
}