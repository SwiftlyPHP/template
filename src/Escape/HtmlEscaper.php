<?php declare(strict_types=1);

namespace Swiftly\Template\Escape;

use Swiftly\Template\ConfigurableInterface;
use Swiftly\Template\EscapeInterface;

use function htmlentities;

use const ENT_HTML5;
use const ENT_QUOTES;

/**
 * Escapes content to make it safe for inclusion in HTML
 *
 * @implements EscapeInterface<string>
 */
class HtmlEscaper implements EscapeInterface, ConfigurableInterface
{
    private int $flags = ENT_QUOTES | ENT_HTML5;

    /**
     * Prepare the given content to be HTML escaped.
     */
    public function __construct(
        private string $content,
    ) {
    }

    /**
     * Set flags to use on calls to `htmlentities`.
     *
     * @return $this
     */
    public function with(int $flags): self
    {
        $this->flags = $flags;

        return $this;
    }

    /**
     * Escape content for HTML.
     */
    public function escape(): string
    {
        return htmlentities($this->content, $this->flags);
    }

    /**
     * Return the short name of this scheme.
     *
     * @psalm-return non-empty-lowercase-string
     */
    public function name(): string
    {
        return 'html';
    }

    public function __toString(): string
    {
        return $this->escape();
    }
}
