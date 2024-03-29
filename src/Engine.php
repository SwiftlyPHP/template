<?php

namespace Swiftly\Template;

use Swiftly\Template\TemplateInterface;
use Swiftly\Template\FileFinder;
use Swiftly\Template\ContextInterface;
use Swiftly\Template\Context\DefaultContext;
use Swiftly\Template\Exception\MissingTemplateException;

/**
 * Facade class used to contextualise and render templates
 */
class Engine implements TemplateInterface
{
    /** @var FileFinder $finder */
    private FileFinder $finder;

    /** @var ContextInterface $context */
    private ContextInterface $context;

    /**
     * Creates a new template engine
     *
     * Allows you to specify how and from where templates are loaded, as well as
     * (optionally) the context they should be rendered in.
     *
     * @no-named-arguments
     * @param FileFinder $finder        Template finder
     * @param ContextInterface $context Rendering context
     */
    public function __construct(
        FileFinder $finder,
        ContextInterface $context = null
    ) {
        $this->finder = $finder;
        $this->context = $context ?: new DefaultContext();
    }

    /**
     * Render the given PHP file and pass the given data
     *
     * @no-named-arguments
     * @throws MissingTemplateException Failed to find template
     * @param string $template          Template name
     * @param mixed[] $variables        Template data
     * @return string                   Rendered template
     */
    public function render(string $template, array $variables = []): string
    {
        $template_path = $this->finder->find($template);

        // Template doesn't exist
        if ($template_path === null) {
            throw new MissingTemplateException($template);
        }

        $context = $this->context->wrap($template_path);

        return $context($variables);
    }
}
