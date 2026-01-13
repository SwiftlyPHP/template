<?php declare(strict_types=1);

namespace Swiftly\Template;

use Stringable;
use Swiftly\Template\Exception\EscapeException;

/**
 * Interface for classes capable of escaping content ready for output
 *
 * @template T
 */
interface EscapeInterface extends Stringable
{
    /**
     * Escape the content using the appropriate scheme
     *
     * @throws EscapeException Failed to escape
     */
    public function escape(): string;

    /**
     * Returns the name of this scheme
     *
     * @psalm-return non-empty-lowercase-string
     */
    public function name(): string;
}
