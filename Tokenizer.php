<?php

namespace Swiftly\Template;

/**
 * Converts a markup file into a stream of tokens
 *
 * @author clvarley
 */
Class Tokenizer
{

    /**
     * Regex for variable name/identifier
     *
     * @var string REGEX_IDENTIFIER
     */
    const REGEX_IDENTIFIER = '[A-Za-z_]+';

    /**
     * Regex for start of echo tag
     *
     * @var string REGEX_OPEN_ECHO
     */
    const REGEX_OPEN_ECHO = '[[';

    /**
    * Regex for end of echo tag
    *
    * @var string REGEX_CLOSE
    */
    const REGEX_CLOSE_ECHO = ']]';

    /**
     * Regex for start of `if` statement
     *
     * @var string REGEX_OPEN_IF
     */
    const REGEX_OPEN_IF = '[%';

    /**
     * Regex for end of `if` statement
     *
     * @var string REGEX_CLOSE_IF
     */
    const REGEX_CLOSE_IF = '%]';

    /**
     * Regex for string literal
     *
     * @var string REGEX_LITERAL_STRING
     */
    const REGEX_LITERAL_STRING = '\".*\"';

    /**
     * Regex for numeric literal
     *
     * @var string REGEX_LITERAL_NUMERIC
     */
    const REGEX_LITERAL_NUMERIC = '(\d{0,}\.)?\d';

    /**
     * The current markup line number
     *
     * @var int $line_number Line number
     */
    private $line_number = 0;

    /**
     * The current markup line offset
     *
     * @var int $offset Line offset
     */
    private $offset = 0;

    /**
     * Currently processing a tag?
     *
     * @var bool $in_tag Processing tag?
     */
    private $in_tag = false;

    /**
     * Compiled regex for markup parsing
     *
     * @var string $regex Compiled regex
     */
    private $regex;

    /**
     * Builds a new tokenizer using the provided options
     *
     * @param array $options (Optional) Tokenizer options
     */
    public function __construct( array $options = [] )
    {
        // TODO: Figure out options passing

        $this->regex = '('
            . \preg_quote( self::REGEX_OPEN_ECHO, '#' )
            .
    }
}
