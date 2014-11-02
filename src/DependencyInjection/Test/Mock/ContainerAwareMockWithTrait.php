<?php

namespace ThinFrame\Applications\DependencyInjection\Test\Mock;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class ContainerAwareMockWithTrait
 *
 * @package ThinFrame\Applications\DependencyInjection\Test\Mock
 */
class ContainerAwareMockWithTrait
{
    use ContainerAwareTrait;

    /**
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }
}