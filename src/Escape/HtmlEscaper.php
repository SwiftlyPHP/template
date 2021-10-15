<?php

namespace Swiftly\Template\Escape;

use Swiftly\Template\EscapeInterface;
use Swiftly\Template\ConfigurableInterface;

use function htmlentities;

use const ENT_QUOTES;
use const ENT_HTML5;

/**
 * Escapes content to make it safe for inclusion in HTML
 *
 * @implements EscapeInterface<string>
 */
Class HtmlEscaper Implements EscapeInterface, ConfigurableInterface
{

    /** @var string $content */
    private $content;

    /** @var int $flags */
    private $flags = ENT_QUOTES | ENT_HTML5;

    /**
     * Prepare the given content to be HTML escaped
     *
     * @param string $content Raw content
     */
    public function __construct( $content )
    {
        $this->content = $content;
    }

    /**
     * Set flags to use on calls to `htmlentities`
     *
     * @param int $flags Encoding flags
     * @return self      Chainable
     */
    public function with( int $flags ) : self
    {
        $this->flags = $flags;

        return $this;
    }

    /**
     * Escape content for HTML
     *
     * @return string Escaped content
     */
    public function escape() : string
    {
        return htmlentities( $this->content, $this->flags );
    }

    public function name() : string
    {
        return 'html';
    }

    public function __toString()
    {
        return $this->escape();
    }
}
