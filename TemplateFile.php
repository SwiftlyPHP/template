<?php

namespace Swiftly\Template;

/**
 * Utility class used to manage template files
 *
 * @author clvarley
 */
Class TemplateFile
{

    /**
     * Path to the template file
     *
     * @var string $filepath File path
     */
    private $filepath;

    /**
     * Creates a wrapper around the given file
     *
     * @param string $filepath File path
     */
    public function __construct( string $filepath )
    {
        $this->filepath = $filepath;
    }
}
