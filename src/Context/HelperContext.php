<?php declare(strict_types=1);

namespace Swiftly\Template\Context;

use Swiftly\Template\ContextInterface;
use Swiftly\Template\Escape\HtmlEscaper;
use Swiftly\Template\Escape\JsonEscaper;
use Swiftly\Template\EscapeInterface;
use Swiftly\Template\Exception\MissingTemplateException;
use Swiftly\Template\Exception\TemplateIncludeException;
use Swiftly\Template\Exception\UnknownSchemeException;

use function array_pop;
use function dirname;
use function end;
use function extract;
use function ob_end_clean;
use function ob_get_contents;
use function ob_start;
use function realpath;

use const EXTR_PREFIX_SAME;

/**
 * Creates a helper context around the PHP file, providing escape utilities
 */
class HelperContext implements ContextInterface
{
    /**
     * Used when including sub-templates, tracks the current template hierarchy.
     *
     * @var list<string>
     */
    private array $stack = [];

    /**
     * @var array<string,class-string<EscapeInterface>>
     */
    private array $schemes = [];

    /**
     * Configure any additional escape schemes for this context.
     *
     * @param array<string,class-string<EscapeInterface>> $schemes
     */
    public function __construct(array $schemes = [])
    {
        foreach ($schemes as $scheme => $class) {
            $this->registerScheme($scheme, $class);
        }
    }

    /**
     * Register a new escape scheme with the context.
     *
     * @param class-string<EscapeInterface> $class
     */
    public function registerScheme(string $scheme, string $class): void
    {
        $this->schemes[$scheme] = $class;
    }

    /**
     * Wraps the template and provides additional helper utilities.
     *
     * @return callable(array<string, mixed>):string
     */
    public function wrap(string $template): callable
    {
        return function (array $variables) use ($template): string {
            $this->enter($template);
            try {
                $output = $this->content($variables);
            } finally {
                $this->leave();
            }
            return $output;
        };
    }

    /**
     * Utility to include a sub-template.
     *
     * The given file path is relative the the template you are including from,
     * allowing you to avoid use of constants such as `__DIR__` or the `dirname`
     * function.
     *
     * @throws TemplateIncludeException Called from non-template context
     * @throws MissingTemplateException Template cannot be found
     *
     * @param array<string, mixed> $variables
     */
    public function include(string $filePath, array $variables = []): string
    {
        if (empty($this->stack)) {
            throw new TemplateIncludeException($this);
        }

        $absolutePath = $this->realpath($filePath);

        if (empty($absolutePath)) {
            throw new MissingTemplateException($filePath);
        }

        return $this->wrap($absolutePath)($variables);
    }

    /**
     * Escape the given string to make it safe for use in HTML.
     */
    public function escapeHtml(string $content): HtmlEscaper
    {
        return new HtmlEscaper($content);
    }

    /**
     * Convert the given content into a JSON string.
     *
     * @return JsonEscaper   JSON escape context
     */
    public function escapeJson(mixed $content): JsonEscaper
    {
        return new JsonEscaper($content);
    }

    /**
     * Escape the given content with the names scheme.
     *
     * @template T
     *
     * @throws UnknownSchemeException Failed to find scheme
     *
     * @param T $content
     *
     * @return EscapeInterface<T>
     */
    public function escape(string $scheme, mixed $content): EscapeInterface
    {
        if (!isset($this->schemes[$scheme])) {
            throw new UnknownSchemeException($scheme);
        }

        return new $this->schemes[$scheme]($content);
    }

    /**
     * Attempt to return the fully qualified file path.
     *
     * Return the absolute path to the given file when taken as relative to the
     * template currently on top of the stack.
     */
    private function realpath(string $filePath): ?string
    {
        $currentTemplate = $this->current();
        $currentDirectory = dirname($currentTemplate);

        return realpath("{$currentDirectory}/{$filePath}") ?: null;
    }

    /**
     * Load the top-most template and return any rendered content.
     *
     * @param array<string,mixed> $variables
     */
    private function content(array $variables): string
    {
        extract($variables, EXTR_PREFIX_SAME, '_');
        require $this->current();
        return ob_get_contents() ?: '';
    }

    /**
     * Return the path of the template currently being processed.
     */
    private function current(): string
    {
        return end($this->stack);
    }

    /**
     * Push a new template onto the end of the template hierarchy stack.
     */
    private function enter(string $filePath): void
    {
        $this->stack[] = $filePath;
        ob_start();
    }

    /**
     * Pop the top-most template from the hierarchy stack.
     */
    private function leave(): void
    {
        array_pop($this->stack);
        ob_end_clean();
    }
}
