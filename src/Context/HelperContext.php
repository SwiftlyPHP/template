<?php

namespace Swiftly\Template\Context;

use Swiftly\Template\ContextInterface;
use Swiftly\Template\Escape\AttributeEscaper;
use Swiftly\Template\Escape\HtmlEscaper;
use Swiftly\Template\Escape\JsonEscaper;

/**
 * Creates a helper context around the PHP file, providing escape utilities
 */
Class HelperContext Implements ContextInterface
{

    public function create( string $file_path ) : callable
    {
        return function ( array $variables ) use ($file_path) : string {
            extract( $variables, EXTR_PREFIX_SAME, '_' );
            ob_start();
            require $file_path;
            return ob_get_clean() ?: '';
        };
    }

    public function escapeAttribute( string $content ) : AttributeEscaper
    {
        return new AttributeEscaper( $content );
    }

    public function escapeHtml( string $content ) : HtmlEscaper
    {
        return new HtmlEscaper( $content );
    }

    public function escapeJson( $content ) : JsonEscaper
    {
        return new JsonEscaper( $content );
    }
}
