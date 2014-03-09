<?php

/**
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Applications\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * ApplicationContainerBuilder - ContainerBuilder with some extra features
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
    public function get($serviceID, $invalidBehavior = ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE)
    {
        $service = parent::get($serviceID, $invalidBehavior);

        $injectionRules = iterator_to_array($this->injectionRules);
        $that           = $this;

        array_walk(
            $injectionRules,
            function (InjectionRuleInterface $injectionRule) use ($service, $that) {
                if ($injectionRule->isValid($service)) {
                    $injectionRule->inject($that, $service);
                }
            }
        );

        return $service;
    }
}
