<?php

namespace ThinFrame\Applications\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ApplicationContainerBuilder
 *
 * @package ThinFrame\Applications\DependencyInjection
 * @since   0.3
 *
 */
class ApplicationContainerBuilder extends ContainerBuilder
{
    /**
     * @var \SplObjectStorage
     */
    private $injectionRules;

    /**
     * Set injection rules
     *
     * @param \SplObjectStorage $injectionRules
     *
     * @return $this
     */
    public function setInjectionRules(\SplObjectStorage $injectionRules)
    {
        $this->injectionRules = $injectionRules;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function get($id, $invalidBehavior = ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE)
    {
        $service = parent::get($id, $invalidBehavior);

        foreach ($this->injectionRules as $injectionRule) {
            /* @var $injectionRule InjectionRuleInterface */
            if ($injectionRule->isValid($service)) {
                $injectionRule->inject($this, $service);
            }
        }

        return $service;
    }
}
