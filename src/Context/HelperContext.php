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

    /**
     * {@inheritdoc}
     */
    public function create( string $file_path ) : callable
    {
        return function ( array $variables ) use ($file_path) : string {
            extract( $variables, EXTR_PREFIX_SAME, '_' );
            ob_start();
            require $file_path;
            return ob_get_clean() ?: '';
        };
    }

    /**
     * Escape the given string to make it safe for use as an attribute value
     *
     * @param string $content   Raw content
     * @return AttributeEscaper Attribute escape context
     */
    public function escapeAttribute( string $content ) : AttributeEscaper
    {
        return new AttributeEscaper( $content );
    }

    /**
     * Escape the given string to make it safe for use in HTML
     *
     * @param string $content Raw content
     * @return HtmlEscaper    HTML escape context
     */
    public function escapeHtml( string $content ) : HtmlEscaper
    {
        return new HtmlEscaper( $content );
    }

    /**
     * Convert the given content into a JSON string
     *
     * @param mixed $content Raw content
     * @return JsonEscaper   JSON escape context
     */
    public function escapeJson( $content ) : JsonEscaper
    {
        return new JsonEscaper( $content );
    }
}
