<?php declare(strict_types=1);

namespace Swiftly\Template\Escape;

use JsonException;
use Swiftly\Template\ConfigurableInterface;
use Swiftly\Template\EscapeInterface;
use Swiftly\Template\Exception\EscapeException;

use function json_encode;

use const JSON_PRESERVE_ZERO_FRACTION;
use const JSON_PRETTY_PRINT;
use const JSON_THROW_ON_ERROR;

/**
 * Escapes content for use as JSON
 *
 * @template T
 * @implements EscapeInterface<T>
 */
class JsonEscaper implements EscapeInterface, ConfigurableInterface
{
    private int $flags = JSON_PRESERVE_ZERO_FRACTION;

    /**
     * Prepare the given content to be converted to JSON.
     *
     * @param T $content
     */
    public function __construct(
        private mixed $content,
    ) {
    }

    /**
     * Set flags to use on calls to `json_encode`.
     *
     * @return $this
     */
    public function with(int $flags): self
    {
        $this->flags = $flags;

        return $this;
    }

    /**
     * Format and prettify the JSON becfore displaying it.
     *
     * @return $this
     */
    public function pretty(): self
    {
        $this->flags = $this->flags | JSON_PRETTY_PRINT;

        return $this;
    }

    /**
     * Escape content as JSON.
     *
     * @throws EscapeException Failed to escape
     */
    public function escape(): string
    {
        try {
            $escaped = json_encode(
                $this->content,
                $this->flags | JSON_THROW_ON_ERROR
            );
        } catch (JsonException $e) {
            throw new EscapeException($this->name(), $this->content, $e);
        }

        return $escaped;
    }

    /**
     * Return the short name of this scheme.
     *
     * @psalm-return non-empty-lowercase-string
     */
    public function name(): string
    {
        return 'json';
    }

    public function __toString()
    {
        return $this->escape();
    }
}
