<?php

/**
 * /src/ThinFrame/Applications/DependencyInjection/Extensions/ConfigurationAware.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

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
