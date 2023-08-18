<?php

namespace Swiftly\Template\Exception;

use RuntimeException;
use Swiftly\Template\ContextInterface;

use function sprintf;
use function get_class;

/**
 * Exception thrown when calling include from a non-template context
 */
class TemplateIncludeException extends RuntimeException
{
    public function __construct(ContextInterface $context)
    {
        parent::__construct(
            'Method %s::include() can only be called from within templates',
            get_class($context)
        );
    }
}
