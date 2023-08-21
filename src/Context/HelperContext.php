<?php

namespace Swiftly\Template\Context;

use Swiftly\Template\ContextInterface;
use Swiftly\Template\EscapeInterface;
use Swiftly\Template\Exception\TemplateIncludeException;
use Swiftly\Template\Exception\MissingTemplateException;
use Swiftly\Template\Escape\HtmlEscaper;
use Swiftly\Template\Escape\JsonEscaper;
use Swiftly\Template\Exception\UnknownSchemeException;

use function dirname;
use function realpath;
use function extract;
use function ob_get_contents;
use function end;
use function ob_start;
use function array_pop;
use function ob_end_clean;

use const EXTR_PREFIX_SAME;

/**
 * Creates a helper context around the PHP file, providing escape utilities
 */
class HelperContext implements ContextInterface
{
    /**
     * Used when including sub-templates, tracks the current template hierarchy
     *
     * @psalm-var list<string> $stack
     * 
     * @var string[] $stack File paths
     */
    private array $stack = [];

    /**
     * Custom escape schemes
     *
     * @psalm-var array<string,class-string<EscapeInterface>> $schemes
     *
     * @var string[] $schemes Additional schemes
     */
    private array $schemes = [];

    /**
     * Configure any additional escape schemes for this context
     *
     * @psalm-param array<string,class-string<EscapeInterface>> $schemes
     *
     * @param string[] $schemes Additional schemes
     */
    public function __construct(array $schemes = [])
    {
        foreach ($schemes as $scheme => $class) {
            $this->registerScheme($scheme, $class);
        }
    }

    /**
     * Register a new escape scheme with the context
     *
     * @psalm-param class-string<EscapeInterface> $class
     *
     * @param string $name   Scheme name
     * @param string $scheme Class name
     */
    public function registerScheme(string $scheme, string $class): void
    {
        $this->schemes[$scheme] = $class;
    }

    /**
     * Wraps the template and provides additional helper utilities
     *
     * @no-named-arguments
     * @psalm-return callable(mixed[]):string
     *
     * @param string $file_path Path to template
     * @return callable         Renderable context
     */
    public function wrap(string $file_path): callable
    {
        return function (array $variables) use ($file_path): string {
            $this->enter($file_path);
            try {
                $output = $this->content($variables);
            } finally {
                $this->leave();
            }
            return $output;
        };
    }

    /**
     * Utility to include a sub-template
     *
     * The given file path is relative the the template you are including from,
     * allowing you to avoid use of constants such as `__DIR__` or the `dirname`
     * function.
     *
     * @no-named-arguments
     *
     * @throws TemplateIncludeException Called from non-template context
     * @throws MissingTemplateException Template cannot be found
     * @param string $file_path         Relative file path
     * @param mixed[] $variables        Template data
     * @return string                   Rendered template
     */
    public function include(string $file_path, array $variables = []): string
    {
        if (empty($this->stack)) {
            throw new TemplateIncludeException($this);
        }

        $absolute_path = $this->realpath($file_path);

        if (empty($absolute_path)) {
            throw new MissingTemplateException($file_path);
        }

        return $this->wrap($absolute_path)($variables);
    }

    /**
     * Escape the given string to make it safe for use in HTML
     *
     * @param string $content Raw content
     * @return HtmlEscaper    HTML escape context
     */
    public function escapeHtml(string $content): HtmlEscaper
    {
        return new HtmlEscaper($content);
    }

    /**
     * Convert the given content into a JSON string
     *
     * @param mixed $content Raw content
     * @return JsonEscaper   JSON escape context
     */
    public function escapeJson($content): JsonEscaper
    {
        return new JsonEscaper($content);
    }

    /**
     * Escape the given content with the names scheme
     *
     * @template T
     * @psalm-param T $content
     * @psalm-return EscapeInterface<T>
     *
     * @throws UnknownSchemeException Failed to find scheme
     * @param string $scheme          Scheme name
     * @param mixed $content          Raw content
     * @return EscapeInterface        Escape context
     */
    public function escape(string $scheme, $content): EscapeInterface
    {
        if (!isset($this->schemes[$scheme])) {
            throw new UnknownSchemeException($scheme);
        }

        return new $this->schemes[$scheme]($content);
    }

    /**
     * Attempt to return the fully qualified file path
     *
     * Return the absolute path to the given file when taken as relative to the
     * template currently on top of the stack.
     * 
     * @param string $file_path Relative file path
     * @return ?string          Absolute file path
     */
    private function realpath(string $file_path): ?string
    {
        $current_template = $this->current();
        $current_directory = dirname($current_template); 

        return realpath("$current_directory/$file_path") ?: null;
    }

    /**
     * Load the top-most template and return any rendered content
     * 
     * @param mixed[] $variables Template data
     * @return string            Captured content
     */
    private function content(array $variables): string
    {
        extract($variables, EXTR_PREFIX_SAME, '_');
        require $this->current();
        return ob_get_contents() ?: '';
    }

    /**
     * Return the path of the template currently being processed
     * 
     * @return string Absolute file path
     */
    private function current(): string
    {
        return end($this->stack);
    }

    /**
     * Push a new template onto the end of the template hierarchy stack
     *
     * @param string $file_path Absolute file path
     */
    private function enter(string $file_path): void
    {
        $this->stack[] = $file_path;
        ob_start();
    }

    /**
     * Pop the top-most template from the hierarchy stack
     */
    private function leave(): void
    {
        array_pop($this->stack);
        ob_end_clean();
    }
}
