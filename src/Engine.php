<?php declare(strict_types=1);

namespace Swiftly\Template;

use Swiftly\Template\Context\DefaultContext;
use Swiftly\Template\ContextInterface;
use Swiftly\Template\Exception\MissingTemplateException;
use Swiftly\Template\FileFinder;
use Swiftly\Template\TemplateInterface;

/**
 * Facade class used to contextualise and render templates
 *
 * @upgrade:php8.1 Mark properties as readonly
 * @upgrade:php8.1 Use `new` to initialise DefaultContext + drop nullable hint
 */
class Engine implements TemplateInterface
{
    private FileFinder $finder;
    private ContextInterface $context;

    /**
     * Creates a new template engine
     *
     * Allows you to specify how and from where templates are loaded, as well as
     * (optionally) the context they should be rendered in.
     */
    public function __construct(
        FileFinder $finder,
        ?ContextInterface $context = null,
    ) {
        $this->finder = $finder;
        $this->context = $context ?: new DefaultContext();
    }

    /**
     * Render the given PHP file and pass the given data
     *
     * @throws MissingTemplateException Failed to find template
     *
     * @param array<string, mixed> $variables
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
