<?php

namespace Swiftly\Template\Escape;

use Swiftly\Template\EscapeInterface;
use Swiftly\Template\ConfigurableInterface;
use JsonException;
use Swiftly\Template\Exception\EscapeException;

use function json_encode;

use const JSON_PRESERVE_ZERO_FRACTION;
use const JSON_THROW_ON_ERROR;

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
     * @throws EscapeException Failed to escape
     * @return string          Escaped content
     */
    public function escape() : string
    {
        try {
            $escaped = json_encode(
                $this->content,
                $this->flags | JSON_THROW_ON_ERROR
            );
        } catch ( JsonException $e ) {
            throw new EscapeException( $this->name(), $this->content, $e );
        }

        return $escaped;
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
