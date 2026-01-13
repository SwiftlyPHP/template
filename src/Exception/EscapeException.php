<?php declare(strict_types=1);

namespace Swiftly\Template\Exception;

use RuntimeException;
use Throwable;

use function sprintf;
use function is_scalar;
use function gettype;

/**
 * Exception thrown when a variable cannot be escaped via a given scheme
 */
class EscapeException extends RuntimeException
{
    /**
     * Indicates that the given content could not be escaped
     */
    public function __construct(
        string $scheme,
        mixed $content,
        ?Throwable $previous = null,
    ) {
        parent::__construct(
            sprintf(
                'Could not escape variable "%s" using the "%s" scheme.',
                is_scalar($content) ? (string)$content : gettype($content),
                $scheme
            ),
            0,
            $previous,
        );
    }
}
