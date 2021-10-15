<?php

namespace Swiftly\Template;

use Swiftly\Template\Exception\MissingTemplateException;

/**
 * Interface to represent that this class can render templates in some manner
 */
Interface TemplateInterface
{

    /**
     * Render the given template with the (optionally) provided data
     *
     * @no-named-arguments
     * @throws MissingTemplateException Failed to find template
     * @param string $template          Template name
     * @param mixed[] $variables        Template data
     * @return string                   Rendered template
     */
    public function render( string $template, array $variables = [] ) : string;

}
