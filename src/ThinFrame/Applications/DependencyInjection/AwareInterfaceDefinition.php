<?php

/**
 * /src/ThinFrame/Applications/DependencyInjection/AwareInterfaceDefinition.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Applications\DependencyInjection;

/**
 * AwareInterfaceDefinition - definition of an aware interface
 *
 * @package ThinFrame\Applications\DependencyInjection
 * @since   0.2
 */
class AwareInterfaceDefinition
{
    /**
     * @var string
     */
    private $interface;
    /**
     * @var string
     */
    private $method;
    /**
     * @var string
     */
    private $service;

    /**
     * Class constructor
     *
     * @param string $interface
     * @param string $method
     * @param string $service
     */
    public function __construct($interface, $method, $service)
    {
        $this->interface = $interface;
        $this->method    = $method;
        $this->service   = $service;
    }

    /**
     * Get interface
     *
     * @return string
     */
    public function getInterface()
    {
        return $this->interface;
    }

    /**
     * Set interface
     *
     * @param string $interface
     */
    public function setInterface($interface)
    {
        $this->interface = $interface;
    }

    /**
     * Get method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set method
     *
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * Get service id
     *
     * @return string
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set service id
     *
     * @param string $service
     */
    public function setService($service)
    {
        $this->service = $service;
    }

    /**
     * Configure application container
     *
     * @param                             $serviceObject
     * @param ApplicationContainerBuilder $builder
     */
    public function configureObject($serviceObject, ApplicationContainerBuilder $builder)
    {
        if ($serviceObject instanceof $this->interface) {
            call_user_func_array([$serviceObject, $this->method], [$builder->get($this->service)]);
        }
    }
}
