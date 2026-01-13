<?php declare(strict_types=1);

namespace Swiftly\Template\Context;

use Swiftly\Template\ContextInterface;

use function extract;
use function ob_get_clean;
use function ob_start;

use const EXTR_PREFIX_SAME;

/**
 * Creates a standard context, treating the file as a regular PHP include
 */
class DefaultContext implements ContextInterface
{
    /**
     * Wraps the template in a standard PHP file include
     *
     * @return callable(array<string,mixed>):string
     */
    public function wrap(string $template): callable
    {
        return static function (array $variables) use ($template): string {
            extract($variables, EXTR_PREFIX_SAME, '_');
            ob_start();
            require $template;
            return ob_get_clean() ?: '';
        };
    }
}
