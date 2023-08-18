<?php

namespace Swiftly\Template\Exception;

use RuntimeException;

use function sprintf;

/**
 * Exception thrown when a requested escape scheme is unavailable
 */
class UnknownSchemeException extends RuntimeException
{
    /**
     * Indicates that the given escape scheme could not be found
     *
     * @param string $scheme      Scheme name
     */
    public function __construct(string $scheme)
    {
        parent::__construct(
            sprintf(
                'Could not find an escape scheme named "%s", are you sure it has been registered?',
                $scheme
            )
        );
    }
}
