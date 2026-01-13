<?php declare(strict_types=1);

namespace Swiftly\Template\Exception;

use RuntimeException;
use Swiftly\Template\ContextInterface;

use function get_class;
use function sprintf;

/**
 * Exception thrown when calling include from a non-template context
 */
class TemplateIncludeException extends RuntimeException
{
    public function __construct(ContextInterface $context)
    {
        parent::__construct(
            sprintf(
                'Method %s::include() can only be called from within templates',
                get_class($context)
            )
        );
    }
}
