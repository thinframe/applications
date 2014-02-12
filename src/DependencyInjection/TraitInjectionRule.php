<?php

namespace ThinFrame\Applications\DependencyInjection;

/**
 * Class TraitInjectionRule
 *
 * @package ThinFrame\Applications\DependencyInjection
 * @since   0.3
 */
class TraitInjectionRule implements InjectionRuleInterface
{
    /**
     * @var string
     */
    private $serviceId;
    /**
     * @var string
     */
    private $traitClass;
    /**
     * @var string
     */
    private $setter;

    /**
     * Constructor
     *
     * @param string $traitClass
     * @param string $serviceId
     * @param string $setter
     */
    public function __construct($traitClass, $serviceId, $setter)
    {
        $this->traitClass = $traitClass;
        $this->serviceId  = $serviceId;
        $this->setter     = $setter;
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
        return in_array(ltrim($this->traitClass, '\\'), class_uses($service));
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
