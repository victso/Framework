<?php

namespace Zoolanders\Service;

use Zoolanders\Container\Container;

class Service
{
    /**
     * @var Container
     */
    protected $container;

    public function __construct(Container $c = null)
    {
        $this->container = $c;
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param Container $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }
}