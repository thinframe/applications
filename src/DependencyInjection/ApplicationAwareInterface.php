<?php

/**
 * src/DependencyInjection/ApplicationAwareInterface.php
 *
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */


namespace ThinFrame\Applications\DependencyInjection;

use ThinFrame\Applications\AbstractApplication;

/**
 * Interface ApplicationAwareInterface
 *
 * @package ThinFrame\Applications\DependencyInjection
 * @since   0.3
 */
interface ApplicationAwareInterface
{
    /**
     * Attach the application to the current instance
     *
     * @param AbstractApplication $application
     *
     * @return $this
     */
    public function setApplication(AbstractApplication $application);
}