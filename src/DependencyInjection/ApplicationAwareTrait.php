<?php

namespace ThinFrame\Applications\DependencyInjection;

use ThinFrame\Applications\AbstractApplication;

/**
 * Class ApplicationAwareTrait
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