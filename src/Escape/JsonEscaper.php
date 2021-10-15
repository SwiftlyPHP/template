<?php

namespace Swiftly\Template\Escape;

use Swiftly\Template\EscapeInterface;
use Swiftly\Template\ConfigurableInterface;

use function json_encode;

use const JSON_PRESERVE_ZERO_FRACTION;

/**
 * Escapes content for use as JSON
 *
 * @implements EscapeInterface<mixed>
 */
Class JsonEscaper Implements EscapeInterface, ConfigurableInterface
{

    /** @var mixed $content */
    private $content;

    /** @var int $flags */
    private $flags = JSON_PRESERVE_ZERO_FRACTION;

    /**
     * Prepare the given content to be converted to JSON
     *
     * @param mixed $content Raw content
     */
    public function __construct( $content )
    {
        $this->content = $content;
    }

    /**
     * Set flags to use on calls to `json_encode`
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
     * Escape content as JSON
     *
     * @return string Escaped content
     */
    public function escape() : string
    {
        return json_encode( $this->content, $this->flags );
    }

    public function name() : string
    {
        return 'json';
    }

    public function __toString()
    {
        return $this->escape();
    }
}
