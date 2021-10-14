<?php

namespace Swiftly\Template;

/**
 * Interface used to represent that this class can create rendering contexts
 */
Interface ContextInterface
{

    /**
     * Creates a new, disposable rendering context
     *
     * @no-named-arguments
     * @psalm-return callable(mixed[]):string
     *
     * @param string $file_path Path to template
     * @return callable         Render function
     */
    public function create( string $template ) : callable;

}
