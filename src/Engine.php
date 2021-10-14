<?php

namespace Swiftly\Template;

use Swiftly\Template\TemplateInterface;
use Swiftly\Template\FileFinder;
use Swiftly\Template\ContextInterface;
use Swiftly\Template\Context\DefaultContext;
use Swiftly\Template\Exception\TemplateNotFoundException;

Class Engine Implements TemplateInterface
{

    /** @var FileFinder $finder */
    private $finder;

    /** @var ContextInterface $context */
    private $context;

    /**
     * Creates a new template engine
     *
     * Allows you to specify how and from where templates are loaded, as well as
     * (optionally) the context they should be rendered in.
     *
     * @param FileFinder $finder        Template finder
     * @param ContextInterface $context Rendering context
     */
    public function __construct(
        FileFinder $finder,
        ContextInterface $context = null
    ) {
        $this->finder = $finder;
        $this->context = $context ?: new DefaultContext();
    }

    /**
     * {@inheritdoc}
     */
    public function render( string $template, array $variables = [] ) : string
    {
        $template = $this->finder->find( $template );

        // Template doesn't exist
        if ( $template === null ) {
            throw new TemplateNotFoundException();
        }

        $context = $this->context->create( $template );

        return $context( $variables );
    }
}
