<?php declare(strict_types=1);

namespace Swiftly\Template;

/**
 * Interface used to represent that this class can create rendering contexts.
 */
interface ContextInterface
{
    /**
     * Wraps the given template in a new disposable rendering context.
     *
     * @return callable(array<string,mixed>):string
     */
    public function wrap(string $template): callable;
}
