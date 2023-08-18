<?php

namespace Swiftly\Template;

/**
 * Interface used to represent that this class can create rendering contexts
 */
interface ContextInterface
{
    /**
     * Wraps the given template in a new disposable rendering context
     *
     * @no-named-arguments
     * @psalm-return callable(mixed[]):string
     *
     * @param string $file_path Path to template
     * @return callable         Renderable context
     */
    public function wrap(string $template): callable;
}
