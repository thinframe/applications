<?php

namespace ThinFrame\Applications;

use PhpCollection\Map;
use ThinFrame\Applications\DependencyInjection\ContainerConfigurator;

class TestApp extends AbstractApplication
{
    /**
     * Get application name
     *
     * @return string
     */
    public function getName()
    {
        // TODO: Implement getName() method.
    }

    /**
     * Get application parents
     *
     * @return AbstractApplication[]
     */
    public function getParents()
    {
        // TODO: Implement getParents() method.
    }

    /**
     * Set different options for the container configurator
     *
     * @param ContainerConfigurator $configurator
     */
    protected function setConfiguration(ContainerConfigurator $configurator)
    {
        $configurator->addResource('test2.yml');
    }

    /**
     * Set application metadata
     *
     * @param Map $metadata
     *
     */
    public function setMetadata(Map $metadata)
    {
        // TODO: Implement setMetadata() method.
    }


}