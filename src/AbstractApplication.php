<?php

namespace ThinFrame\Applications;

use PhpCollection\Map;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use ThinFrame\Applications\DependencyInjection\ContainerConfigurator;
use ThinFrame\Foundation\Exceptions\InvalidArgumentException;
use ThinFrame\Foundation\Exceptions\RuntimeException;

/**
 * Class AbstractApplication
 *
 * @package ThinFrame\Applications
 * @since   0.3
 */
abstract class AbstractApplication
{
    /**
     * @var \ReflectionClass
     */
    protected $reflector;

    /**
     * @var \SplObjectStorage
     */
    private $applications;

    /**
     * @var ContainerConfigurator
     */
    private $configurator;

    /**
     * @var bool
     */
    private $ready = false;

    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @var Map[]
     */
    private $metadata = [];

    /**
     * Constructor
     */
    function __construct()
    {
        $this->reflector    = new \ReflectionClass($this);
        $this->applications = new \SplObjectStorage();
        $this->configurator = new ContainerConfigurator();
    }

    /**
     * Build application structure
     *
     * @return $this
     */
    public function make()
    {
        if (!$this->ready) {

            $this->unifyApplications($this);

            foreach ($this->applications as $application) {
                $this->configurator->setCurrentApplication($application);
                /* @var $application AbstractApplication */
                $application->setConfiguration($this->configurator);

                $this->metadata[$application->getName()] = new Map();

                $this->metadata[$application->getName()]->set('namespace', $application->getNamespace());
                $this->metadata[$application->getName()]->set('path', $application->getPath());

                $application->setMetadata($this->metadata[$application->getName()]);
            }

            $this->container = new ContainerBuilder();

            $this->configurator->configureContainer($this->container);

            $this->container->compile();

            $this->ready = true;
        }


        return $this;
    }

    /**
     * Get application base path
     *
     * @return string
     */
    public function getPath()
    {
        return dirname($this->reflector->getFileName());
    }

    /**
     * Get application namespace
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->reflector->getNamespaceName();
    }

    /**
     * Get application metadata
     *
     * @return \PhpCollection\Map[]
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * Get container
     *
     * @return ContainerBuilder
     * @throws \ThinFrame\Foundation\Exceptions\RuntimeException
     */
    public function getContainer()
    {
        if ($this->ready) {
            return $this->container;
        }
        throw new RuntimeException('The container wasn\'t compiled. Please run make() first');
    }

    /**
     * Unify parent applications tree into a spl object storage for easier manipulation
     *
     * @param AbstractApplication $currentApplication
     * @param array               $loadedApplications
     *
     * @throws \ThinFrame\Foundation\Exceptions\InvalidArgumentException
     */
    private function unifyApplications(AbstractApplication $currentApplication, &$loadedApplications = [])
    {
        $this->applications->attach($currentApplication);
        $loadedApplications[] = get_class($currentApplication);

        foreach ((array)$currentApplication->getParents() as $parentApplication) {
            if (!is_object($parentApplication) || !($parentApplication instanceof AbstractApplication)) {
                throw new InvalidArgumentException('Invalid parent application provided');
            }
            if (!in_array(get_class($parentApplication), $loadedApplications)) {
                $this->unifyApplications($parentApplication, $loadedApplications);
            }
        }
    }

    /**
     * Get application name
     *
     * @return string
     */
    abstract public function getName();

    /**
     * Get application parents
     *
     * @return AbstractApplication[]
     */
    abstract public function getParents();

    /**
     * Set different options for the container configurator
     *
     * @param ContainerConfigurator $configurator
     */
    abstract protected function setConfiguration(ContainerConfigurator $configurator);

    /**
     * Set application metadata
     *
     * @param Map $metadata
     *
     */
    abstract protected function setMetadata(Map $metadata);
}