<?php declare(strict_types=1);

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
