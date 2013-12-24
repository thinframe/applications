<?php
namespace ThinFrame\Applications\DependencyInjection\Extensions;

/**
 * Interface ConfigurationAware
 *
 * @package ThinFrame\Applications\DependencyInjection\Extensions
 * @since   0.2
 */
interface ConfigurationAware
{
    /**
     * @param array $configuration
     *
     */
    public function setConfiguration(array $configuration);
}