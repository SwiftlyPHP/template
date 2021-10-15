<?php

namespace Swiftly\Template\Exception;

use RuntimeException;
use Throwable;

use function sprintf;
use function is_scalar;
use function gettype;

/**
 * Exception thrown when a variable cannot be escaped via a given scheme
 */
Class EscapeException Extends RuntimeException
{

    /**
     * Indicates that the given content could not be escaped
     *
     * @param string $scheme      Scheme name
     * @param mixed $content      Raw content
     * @param Throwable $previous Previous throwable
     */
    public function __construct( string $scheme, $content, Throwable $previous = null )
    {
        parent::__construct(
            sprintf(
                'Could not escape variable "%s" using the "%s" scheme.',
                is_scalar( $content ) ? (string)$content : gettype( $content ),
                $scheme
            ),
            0,
            $previous
        );
    }
}
