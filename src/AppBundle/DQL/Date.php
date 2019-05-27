<?php

namespace AppBundle\DQL;

use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\Parser;

/**
 * Class Date
 *
 * Adds the hability to use the MySQL DATE function inside Doctrine
 *
 * @package Vf\Bundle\VouchedforBundle\DQL
 */
class Date extends FunctionNode
{

    /**
     * Holds the timestamp of the DATE DQL statement
     * @var $dateExpression
     */
    protected $dateExpression;

    public function getSql( SqlWalker $sqlWalker )
    {
        return 'DATE (' . $sqlWalker->walkArithmeticExpression( $this->dateExpression ) . ')';
    }

    /**
     * @param Parser $parser
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function parse(Parser $parser )
    {
        $parser->Match( Lexer::T_IDENTIFIER );
        $parser->Match( Lexer::T_OPEN_PARENTHESIS );

        $this->dateExpression = $parser->ArithmeticExpression();

        $parser->Match( Lexer::T_CLOSE_PARENTHESIS );
    }
}