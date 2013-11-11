<?php

/**
 * /src/ThinFrame/Applications/DependencyInjection/ContainerConfigurator.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Applications\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

/**
 * ContainerConfigurator - configure container builder with extensions and compiler passes
 *
 * @package ThinFrame\Applications\DependencyInjection
 * @since   0.2
 */
class ContainerConfigurator
{
    /**
     * @var AwareInterfaceDefinition[]
     */
    private $awareInterfacesDefinitions = [];
    /**
     * @var ExtensionInterface[]
     */
    private $extensions = [];
    /**
     * @var CompilerPassInterface[]
     */
    private $compilerPasses = [];

    /**
     * Add aware interface definition
     *
     * @param AwareInterfaceDefinition $definition
     */
    public function addAwareInterfaceDefinition(AwareInterfaceDefinition $definition)
    {
        $this->awareInterfacesDefinitions[] = $definition;
    }

    /**
     * Add extension
     *
     * @param ExtensionInterface $extension
     */
    public function addExtension(ExtensionInterface $extension)
    {
        $this->extensions[] = $extension;
    }

    /**
     * Add compiler pass
     *
     * @param CompilerPassInterface $compilerPass
     */
    public function addCompilerPass(CompilerPassInterface $compilerPass)
    {
        $this->compilerPasses[] = $compilerPass;
    }

    /**
     * Configure container
     *
     * @param ApplicationContainerBuilder $container
     */
    public function configureContainer(ApplicationContainerBuilder $container)
    {
        foreach ($this->getExtensions() as $extension) {
            $container->registerExtension($extension);
        }
        foreach ($this->getCompilerPasses() as $compilerPass) {
            $container->addCompilerPass($compilerPass);
        }
        foreach ($this->getAwareInterfacesDefinitions() as $definition) {
            $container->addAwareInterfaceDefinition($definition);
        }
    }

    /**
     * Get all extensions
     *
     * @return ExtensionInterface[]
     */
    public function getExtensions()
    {
        return $this->extensions;
    }

    /**
     * Get all compiler passes
     *
     * @return CompilerPassInterface[]
     */
    public function getCompilerPasses()
    {
        return $this->compilerPasses;
    }

    /**
     * Get all aware interface definitions
     *
     * @return AwareInterfaceDefinition[]
     */
    public function getAwareInterfacesDefinitions()
    {
        return $this->awareInterfacesDefinitions;
    }
}
