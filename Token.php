<?php

namespace Swiftly\Template;

/**
 * Represents a single template token
 *
 * @author clvarley
 */
Class Token
{

    /**
     * Type of this token
     *
     * @var string $type Token type
     */
    public $type;

    /**
     * The line on which this token was found
     *
     * @var int $line_number Line number
     */
    public $line_number;

    /**
     * The offset on which this token was found
     *
     * @var int $offset Line offset
     */
    public $offset;

    /**
     * The content of the token
     *
     * @var string $content Token content
     */
    public $content;

}
