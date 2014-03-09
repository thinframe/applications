<?php

/**
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Applications\DependencyInjection;

use ThinFrame\Applications\AbstractApplication;

/**
 * ApplicationAwareTrait - should be used by classes that depends on AbstractApplication
 *
 * @package ThinFrame\Applications\DependencyInjection
 * @since   0.3
 */
trait ApplicationAwareTrait
{
    /**
     * @var AbstractApplication
     */
    protected $application;

    /**
     * Attach the application to the current instance
     *
     * @param AbstractApplication $application
     *
     * @return $this
     */
    public function setApplication(AbstractApplication $application)
    {
        $this->application = $application;

        return $this;
    }
}
