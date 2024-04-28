<?php

namespace App\Query;

use Doctrine\ORM\Query\AST\ASTException;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\SimpleArithmeticExpression;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\TokenType;

class Rand extends FunctionNode
{
    private ?SimpleArithmeticExpression $expression = null;

    /**
     * @throws ASTException
     */
    public function getSql(SqlWalker $sqlWalker): string
    {
        if ($this->expression !== null) {
            return 'RAND(' . $this->expression->dispatch($sqlWalker) . ')';
        }

        return 'RAND()';
    }

    /**
     * @throws QueryException
     */
    public function parse(Parser $parser): void
    {
        $lexer = $parser->getLexer();
        $parser->match(TokenType::T_IDENTIFIER);
        $parser->match(TokenType::T_OPEN_PARENTHESIS);

        if ($lexer->lookahead->type !== TokenType::T_CLOSE_PARENTHESIS) {
            $this->expression = $parser->SimpleArithmeticExpression();
        }

        $parser->match(TokenType::T_CLOSE_PARENTHESIS);
    }
}
