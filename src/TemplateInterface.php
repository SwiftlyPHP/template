<?php declare(strict_types=1);

namespace Swiftly\Template;

use Swiftly\Template\Exception\RenderException;
use Swiftly\Template\Exception\MissingTemplateException;

/**
 * Interface to represent that this class can render templates in some manner
 */
interface TemplateInterface
{
    /**
     * Render the given template with the (optionally) provided data
     *
     * @no-named-arguments
     * @throws RenderException          Failed to render
     * @throws MissingTemplateException Failed to find template
     * @param string $template          Template name
     * @param mixed[] $variables        Template data
     * @return string                   Rendered template
     */
    public function render(string $template, array $variables = []): string;
}
