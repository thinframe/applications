<?php

namespace ThinFrame\Applications\DependencyInjection;

/**
 * Interface InjectionRuleInterface
 *
 * @package Symfony\Component\DependencyInjection
 * @since   0.3
 */
interface InjectionRuleInterface
{
    /**
     * Check if service match injection rule
     *
     * @param object $service
     *
     * @return bool
     */
    public function isValid($service);

    /**
     * Inject service
     *
     * @param ApplicationContainerBuilder $container
     * @param object                      $service
     *
     */
    public function inject(ApplicationContainerBuilder $container, $service);
}
