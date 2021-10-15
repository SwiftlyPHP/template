<?php

namespace Swiftly\Template\Exception;

use RuntimeException;

use function sprintf;

/**
 * Exception thrown when a template cannot be found
 */
Class MissingTemplateException Extends RuntimeException
{

    /**
     * Indicates that the given template could not be found
     *
     * @param string $template Template name
     */
    public function __construct( string $template )
    {
        parent::__construct(
            sprintf(
                'Could not find template "%s" on the filesystem, are you sure it exists?',
                $template
            )
        );
    }
}
