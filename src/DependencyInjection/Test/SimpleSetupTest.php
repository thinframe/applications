<?php

namespace ThinFrame\Applications\DependencyInjection\Test;

use ThinFrame\Applications\AbstractApplication;
use ThinFrame\Applications\DependencyInjection\ApplicationContainerBuilder;
use ThinFrame\Applications\DependencyInjection\Test\Applications\BareApp;
use ThinFrame\Foundation\Exception\RuntimeException;

/**
 * Class SimpleSetupTest
 *
 * @package ThinFrame\Applications\DependencyInjection\Test
 */
class SimpleSetupTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AbstractApplication
     */
    protected $application;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->application = new BareApp();
    }

    /**
     * Test if class has the correct name
     */
    public function testApplicationHasTheCorrectName()
    {
        $this->assertEquals('BareApp', $this->application->getName());
    }

    /**
     * Test application metadata
     */
    public function testApplicationShouldContainMinimumAmountOfMetadata()
    {
        try {
            $this->application->getMetadata();
            $this->assertTrue(false, "Shouldn\'t be able to retrieve medatada if container not compiler");
        } catch (RuntimeException $exception) {
            $this->assertTrue(true);
        }

        $this->application->make();

        $counter = 0;

        foreach ($this->application->getMetadata() as $name => $medatada) {
            $counter++;
            $this->assertEquals($name, $this->application->getName(), 'Metadata key doesn\'t match expected value');
            $this->assertNotNull($medatada->get('namespace')->getOrElse(null), 'Metadata key is missing');
            $this->assertNotNull($medatada->get('path')->getOrElse(null), 'Metadata key is missing');

            $reflector = new \ReflectionClass($this->application);

            $this->assertEquals(
                $medatada->get('namespace')->getOrElse(null),
                $reflector->getNamespaceName(),
                'Data mismatch for metadata'
            );
            $this->assertEquals(
                $medatada->get('path')->getOrElse(null),
                dirname($reflector->getFileName()),
                'Data mismatch for metadata'
            );
        }

        $this->assertGreaterThan(0, $counter, 'Metadata empty');
    }

    /**
     * Test application container
     */
    public function testApplicationContainer()
    {
        try {
            $this->application->getContainer();
            $this->assertTrue(false, "Shouldn\'t be able to retrieve container if not compiler");
        } catch (RuntimeException $exception) {
            $this->assertTrue(true);
        }

        $container = $this->application->make()->getContainer();

        $this->assertTrue($container instanceof ApplicationContainerBuilder, 'Container should have the right type');

        $this->assertTrue(
            $container->get('container') instanceof ApplicationContainerBuilder,
            'Container service should have the right type'
        );
        $this->assertTrue(
            $container->get('service_container') instanceof ApplicationContainerBuilder,
            'Container service should have the right type'
        );

        $this->assertTrue(
            $container->get('application') instanceof BareApp,
            'Application service should have the right type'
        );
    }

    /**
     * Test application details
     */
    public function testApplicationDetails()
    {
        $reflector = new \ReflectionClass($this->application);

        $this->assertEquals(
            dirname($reflector->getFileName()),
            $this->application->getPath(),
            'Application path mismatch'
        );
        $this->assertEquals(
            $reflector->getNamespaceName(),
            $this->application->getNamespace(),
            'Application namespace mismatch'
        );

        $this->assertCount(0, $this->application->getParents(), 'Application parents mismatch');

    }
}
