<?php

namespace Swiftly\Template\Context;

use Swiftly\Template\ContextInterface;
use Swiftly\Template\EscapeInterface;
use Swiftly\Template\Exception\MissingTemplateException;
use Swiftly\Template\Exception\TemplateIncludeException;
use Swiftly\Template\Escape\HtmlEscaper;
use Swiftly\Template\Escape\JsonEscaper;
use Swiftly\Template\Exception\UnknownSchemeException;

use function extract;
use function ob_start;
use function ob_get_clean;
use function array_pop;
use function end;
use function dirname;
use function realpath;
use function is_file;

use const EXTR_PREFIX_SAME;

/**
 * Creates a helper context around the PHP file, providing escape utilities
 */
class HelperContext implements ContextInterface
{
    /**
     * Used when including sub-templates, tracks the current template hierarchy
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
            $this->stack[] = $file_path;
            $output = '';
            {
                extract($variables, EXTR_PREFIX_SAME, '_');
                ob_start();
                require $file_path;
                $output = ob_get_clean() ?: '';
            }
            array_pop($this->stack);
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
     * @param string $file_path  Relative file path
     * @param mixed[] $variables Template data
     * @return string            Rendered template
     */
    public function include(string $file_path, array $variables): string
    {
        if (empty($this->stack)) {
            throw new TemplateIncludeException($this);
        }

        // The passed $file_path should be considered relative to the template
        // currently being rendered
        $current_template = end($this->stack);
        $current_directory = dirname($current_template);
        $absolute_path = realpath("$current_directory/$file_path");

        if (empty($absolute_path)) {
            throw new MissingTemplateException($absolute_path);
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
}
