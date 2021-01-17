<?php

namespace Swiftly\Template;

/**
 * Interface for all classes that can render templates
 *
 * @author clvarley
 */
Interface TemplateInterface
{

    /**
     * Render the given template file
     *
     * @param string $filepath File path
     * @param array $data      (Optional) Template data
     * @return string          Rendered file
     */
    public function render( string $filepath, array $data = [] ) : string;

}
