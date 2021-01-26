<?php

namespace Swiftly\Template;

use Swiftly\Template\{
    Compiler,
    Parser,
    Tokenizer
};

/**
 * Provides a facade for working with the template system
 *
 * @author clvarley
 */
Class Engine Extends TemplateInterface
{

    /**
     * Converts templates into a stream of tokens
     *
     * @var Tokenizer $tokenizer Template tokenizer
     */
    private $tokenizer;

    /**
     * Parses tokens into an AST
     *
     * @var Parser $parser Token parser
     */
    private $parser;

    /**
     * Compiles AST into usable form
     *
     * @var Compiler $compiler AST compiler
     */
    private $compiler;

    /**
     * Creates a new utility wrapper for working with templates
     *
     * @param Tokenizer $tokenizer Template tokenizer
     * @param Parser $parser       Token parser
     * @param Compiler $compiler   AST compiler
     */
    public function __construct( Tokenizer $tokenizer, Parser $parser, Compiler $compiler )
    {
        $this->tokenizer = $tokenizer;
        $this->parser = $parser;
        $this->compiler = $compiler;
    }

    /**
     * Attempts to render the given Swiftly template file
     *
     * @param string $filepath File path
     * @param array $data      (Optional) Template data
     * @return string          Rendered template
     */
    public function render( string $filepath, array $data = [] ) : string
    {
        // TODO
    }
}
