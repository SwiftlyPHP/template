<?php

namespace Swiftly\Template\Context;

use Swiftly\Template\ContextInterface;

use function extract;
use function ob_start;
use function ob_get_clean;

use const EXTR_PREFIX_SAME;

/**
 * Creates a standard context, treating the file as a regular PHP include
 */
Class DefaultContext Implements ContextInterface
{

    /**
     * {@inheritdoc}
     */
    public function create( string $file_path ) : callable
    {
        return static function ( array $variables ) use ($file_path) : string {
            extract( $variables, EXTR_PREFIX_SAME, '_' );
            ob_start();
            require $file_path;
            return ob_get_clean() ?: '';
        };
    }
}
