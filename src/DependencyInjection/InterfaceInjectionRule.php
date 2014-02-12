<?php

namespace ThinFrame\Applications\DependencyInjection;

/**
 * Class InterfaceInjectionRule
 *
 * @package ThinFrame\Applications\DependencyInjection
 * @since   0.3
 */
class InterfaceInjectionRule implements InjectionRuleInterface
{
    /**
     * @var string
     */
    private $interfaceClass;

    /**
     * @var string
     */
    private $serviceId;

    /**
     * @var string
     */
    private $setter;

    /**
     * Constructor
     *
     * @param string $interfaceClass
     * @param string $serviceId
     * @param string $setter
     */
    public function __construct($interfaceClass, $serviceId, $setter)
    {
        $this->interfaceClass = $interfaceClass;
        $this->serviceId      = $serviceId;
        $this->setter         = $setter;
    }

    /**
     * Check if service match injection rule
     *
     * @param object $service
     *
     * @return bool
     */
    public function isValid($service)
    {
        return ($service instanceof $this->interfaceClass);
    }

    /**
     * Inject service
     *
     * @param ApplicationContainerBuilder $container
     * @param object                      $service
     *
     */
    public function inject(ApplicationContainerBuilder $container, $service)
    {
        call_user_func_array([$service, $this->setter], [$container->get($this->serviceId)]);
    }
}
