<?php

/**
 * /src/ThinFrame/Applications/AbstractApplication.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Applications;

use PhpCollection\Sequence;
use Symfony\Component\Config\FileLocator;
use ThinFrame\Applications\DependencyInjection\ApplicationContainerBuilder;
use ThinFrame\Applications\DependencyInjection\ContainerConfigurator;

/**
 * Class AbstractApplication
 *
 * @package ThinFrame\Applications
 * @since   0.2
 */
abstract class AbstractApplication
{
    /**
     * @var \ReflectionClass
     */
    private $reflector;
    /**
     * @var ContainerConfigurator
     */
    private $containerConfigurator;
    /**
     * @var Sequence<AbstractApplication>
     */
    private $parentApplications;
    /**
     * @var ApplicationContainerBuilder
     */
    private $containerBuilder;
    /**
     * @var bool
     */
    private $containerBuilderCompiled = false;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->reflector = new \ReflectionClass(get_called_class());

        $this->parentApplications = new Sequence();

        $this->parentApplications = $this->getParentApplications();

        $this->containerConfigurator = new ContainerConfigurator();

        $this->initializeConfigurator($this->containerConfigurator);

        $this->containerBuilder = new ApplicationContainerBuilder(new FileLocator($this->getApplicationPath()));

    }

    /**
     * Get parent applications
     *
     * @return AbstractApplication[]
     */
    abstract protected function getParentApplications();

    /**
     * initialize configurator
     *
     * @param ContainerConfigurator $configurator
     *
     * @return mixed
     */
    abstract public function initializeConfigurator(ContainerConfigurator $configurator);

    /**
     * Get application path
     *
     * @return string
     */
    public function getApplicationPath()
    {
        return dirname($this->reflector->getFileName());
    }

    /**
     * @param array $loadedApplications
     *
     * @return ApplicationContainerBuilder
     */
    public function getApplicationContainer(&$loadedApplications = array())
    {
        if (!$this->containerBuilderCompiled) {
            foreach ($this->parentApplications as $app) {
                if (in_array(get_class($app), $loadedApplications)) {
                    continue;
                }
                $loadedApplications[] = get_class($app);
                $this->containerBuilder->merge($app->getApplicationContainer($loadedApplications));
            }

            $this->configure($this->containerBuilder);

            foreach ($this->getConfigurationFiles() as $file) {
                $this->containerBuilder->import($file);
            }
            $this->containerBuilder->compile();
        }
        return $this->containerBuilder;
    }

    /**
     * Configure Application container
     *
     * @param ApplicationContainerBuilder $container
     */
    public function configure(ApplicationContainerBuilder $container)
    {
        foreach ($this->parentApplications as $application) {
            /* @var $application AbstractApplication */
            $application->configure($container);
        }
        $this->getContainerConfigurator()->configureContainer($container);
    }

    /**
     * Get container configurator
     *
     * @return ContainerConfigurator
     */
    public function getContainerConfigurator()
    {
        return $this->containerConfigurator;
    }

    /**
     * Get configuration files
     *
     * @return mixed
     */
    abstract public function getConfigurationFiles();

    /**
     * Get namespace
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->reflector->getNamespaceName();
    }

    /**
     * Get application name
     *
     * @return string
     */
    abstract public function getApplicationName();
}
