<?php

/**
 * src/DependencyInjection/ContainerConfigurator.php
 *
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Applications\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use ThinFrame\Applications\AbstractApplication;
use ThinFrame\Foundation\Exceptions\RuntimeException;

/**
 * Class ContainerConfigurator
 *
 * @package ThinFrame\Applications\DependencyInjection
 * @since   0.3
 */
class ContainerConfigurator
{
    /**
     * @var \SplObjectStorage
     */
    private $extensions;
    /**
     * @var \SplObjectStorage
     */
    private $compilerPasses;

    /**
     * @var \SplObjectStorage
     */
    private $injectionRules;

    /**
     * @var array
     */
    private $resources = [];

    /**
     * @var AbstractApplication
     */
    private $currentApplication;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->extensions     = new \SplObjectStorage();
        $this->compilerPasses = new \SplObjectStorage();
        $this->injectionRules = new \SplObjectStorage();
    }

    /**
     * Set current application
     *
     * @param AbstractApplication $application
     */
    public function setCurrentApplication(AbstractApplication $application)
    {
        $this->currentApplication = $application;
    }

    /**
     * Add a new extension
     *
     * @param ExtensionInterface $extension
     *
     * @return $this
     */
    public function addExtension(ExtensionInterface $extension)
    {
        $this->extensions->attach($extension);

        return $this;
    }

    /**
     * Get extensions storage
     *
     * @return \SplObjectStorage
     */
    public function getExtensions()
    {
        return $this->extensions;
    }

    /**
     * Add injection rule
     *
     * @param InjectionRuleInterface $injectionRule
     *
     * @return $this
     */
    public function addInjectionRule(InjectionRuleInterface $injectionRule)
    {
        $this->injectionRules->attach($injectionRule);

        return $this;
    }

    /**
     * Get injection rules
     *
     * @return \SplObjectStorage
     */
    public function getInjectionRules()
    {
        return $this->injectionRules;
    }

    /**
     * Add a new compiler pass
     *
     * @param CompilerPassInterface $compilerPass
     *
     * @return $this
     */
    public function addCompilerPass(CompilerPassInterface $compilerPass)
    {
        $this->compilerPasses->attach($compilerPass);

        return $this;
    }

    /**
     * Get compiler passes
     *
     * @return \SplObjectStorage
     */
    public function getCompilerPasses()
    {
        return $this->compilerPasses;
    }

    /**
     * Add resource
     *
     * @param string $resourcePath
     *
     * @return $this
     */
    public function addResource($resourcePath)
    {
        if (!isset($this->resources[$this->currentApplication->getPath()])) {
            $this->resources[$this->currentApplication->getPath()] = [];
        }
        $this->resources[$this->currentApplication->getPath()][] = $resourcePath;

        return $this;
    }

    /**
     * Add resources
     *
     * @param array $resources
     *
     * @return $this
     */
    public function addResources(array $resources)
    {
        array_walk($resources, [$this, 'addResource']);

        return $this;
    }

    /**
     * Configure container
     *
     * @param ApplicationContainerBuilder $container
     */
    public function configureContainer(ApplicationContainerBuilder $container)
    {
        foreach ($this->extensions as $extension) {
            $container->registerExtension($extension);
        }

        foreach ($this->compilerPasses as $compilerPass) {
            $container->addCompilerPass($compilerPass);
        }

        $container->setInjectionRules($this->injectionRules);

        $this->resources = array_reverse($this->resources, true);

        foreach ($this->resources as $basePath => $resources) {
            $fileLocator = new FileLocator($basePath);
            array_walk(
                array_reverse($resources),
                function ($resourcePath) use ($fileLocator, $container) {
                    switch (pathinfo($resourcePath, PATHINFO_EXTENSION)) {
                        case 'yml':
                            $loader = new YamlFileLoader($container, $fileLocator);
                            break;
                        case 'xml':
                            $loader = new XmlFileLoader($container, $fileLocator);
                            break;
                        case 'php':
                            $loader = new PhpFileLoader($container, $fileLocator);
                            break;
                        default:
                            throw new RuntimeException('Resource type not supported: ' . $resourcePath);
                    }
                    $loader->load($resourcePath);
                }
            );
        }
    }
}
