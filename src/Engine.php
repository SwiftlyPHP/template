<?php

namespace Swiftly\Template;

use Swiftly\Template\TemplateInterface;
use Swiftly\Template\LoaderInterface;
use Swiftly\Template\ContextInterface;
use Swiftly\Template\Context\DefaultContext;

Class Engine Implements TemplateInterface
{

    /** @var LoaderInterface $loader */
    private $loader;

    /** @var ContextInterface $context */
    private $context;

    /**
     * Creates a new template engine
     *
     * Allows you to specify how and from where templates are loaded, as well as
     * (optionally) the context they should be rendered in.
     *
     * @param LoaderInterface $loader   Template loader
     * @param ContextInterface $context Rendering context
     */
    public function __construct(
        LoaderInterface $loader,
        ContextInterface $context = null
    ) {
        $this->loader = $loader;
        $this->context = $context ?: new DefaultContext();
    }

}
