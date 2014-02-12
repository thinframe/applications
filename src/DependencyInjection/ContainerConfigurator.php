<?php

namespace ThinFrame\Applications\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
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
     */
    public function addExtension(ExtensionInterface $extension)
    {
        $this->extensions->attach($extension);
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
     * Add a new compiler pass
     *
     * @param CompilerPassInterface $compilerPass
     */
    public function addCompilerPass(CompilerPassInterface $compilerPass)
    {
        $this->compilerPasses->attach($compilerPass);
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
     */
    public function addResource($resourcePath)
    {
        if (!isset($this->resources[$this->currentApplication->getPath()])) {
            $this->resources[$this->currentApplication->getPath()] = [];
        }
        $this->resources[$this->currentApplication->getPath()][] = $resourcePath;
    }

    /**
     * Add resources
     *
     * @param array $resources
     */
    public function addResources(array $resources)
    {
        array_walk($resources, [$this, 'addResource']);
    }

    /**
     * Configure container
     *
     * @param ContainerBuilder $container
     */
    public function configureContainer(ContainerBuilder $container)
    {
        array_walk(iterator_to_array($this->extensions), [$container, 'registerExtension']);
        array_walk(iterator_to_array($this->compilerPasses), [$container, 'addCompilerPass']);

        $this->resources = array_reverse($this->resources, true);

        foreach ($this->resources as $basePath => $resources) {
            $fileLocator = new FileLocator($basePath);
            array_walk(
                array_reverse($resources),
                function ($resourcePath) use ($fileLocator, $container) {
                    $loader = null;
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
