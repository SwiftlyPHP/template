<?php

namespace Swiftly\Template\Context;

use Swiftly\Template\ContextInterface;
use Swiftly\Template\EscapeInterface;
use Swiftly\Template\Escape\HtmlEscaper;
use Swiftly\Template\Escape\JsonEscaper;
use Swiftly\Template\Exception\UnknownSchemeException;

use function extract;
use function ob_start;
use function ob_get_clean;

use const EXTR_PREFIX_SAME;

/**
 * Creates a helper context around the PHP file, providing escape utilities
 */
class HelperContext implements ContextInterface
{
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
            extract($variables, EXTR_PREFIX_SAME, '_');
            ob_start();
            require $file_path;
            return ob_get_clean() ?: '';
        };
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
