<?php

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
     * Wrap the given value to be escaped later
     *
     * @psalm-param T $content
     *
     * @param mixed $content Raw content
     */
    public function __construct($content);

    /**
     * Escape the content using the appropriate scheme
     *
     * @throws EscapeException Failed to escape
     * @return string          Escaped content
     */
    public function escape(): string;

    /**
     * Returns the name of this scheme
     *
     * @psalm-return non-empty-lowercase-string
     *
     * @return string Scheme name
     */
    public function name(): string;
}
