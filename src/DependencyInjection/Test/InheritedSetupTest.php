<?php

namespace ThinFrame\Applications\DependencyInjection\Test;

use ThinFrame\Applications\DependencyInjection\ApplicationContainerBuilder;
use ThinFrame\Applications\DependencyInjection\Test\Applications\ChildApp;
use ThinFrame\Foundation\Exception\RuntimeException;

/**
 * Class InheritedSetupTest
 *
 * @package ThinFrame\Applications\DependencyInjection\Test
 */
class InheritedSetupTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ChildApp
     */
    protected $application;

    /**
     * Set up
     */
    protected function setUp()
    {
        $this->application = new ChildApp();
    }

    /**
     * Test application details
     */
    public function testApplicationDetails()
    {
        $reflector = new \ReflectionClass($this->application);
        $this->assertEquals('ChildApp', $this->application->getName(), 'Application details mismatch');
        $this->assertEquals(
            dirname($reflector->getFileName()),
            $this->application->getPath(),
            'Application details mismatch'
        );
        $this->assertEquals(
            $reflector->getNamespaceName(),
            $this->application->getNamespace(),
            'Application details mismatch'
        );
    }

    /**
     * Test application metadata
     */
    public function testApplicationMetadata()
    {
        try {
            $this->application->getMetadata();
            $this->assertTrue(false, 'Metadata returned without compiling');
        } catch (RuntimeException $exception) {
            $this->assertTrue(true);
        }
        $this->application->make();

        $this->assertArrayHasKey('ChildApp', $this->application->getMetadata(), 'Missing app metadata');
        $this->assertArrayHasKey('ParentApp', $this->application->getMetadata(), 'Missing app metadata');

        $childAppMetadata = $this->application->getMetadata()['ChildApp'];

        $this->assertEquals(
            'test.child.metadata.value',
            $childAppMetadata->get('test.child.metadata')->getOrElse(null)
        );

        $parentAppMetadata = $this->application->getMetadata()['ParentApp'];

        $this->assertEquals(
            'test.metadata.value',
            $parentAppMetadata->get('test.metadata')->getOrElse(null)
        );
    }

    /**
     * Test container parameters
     */
    public function testContainerParameters()
    {
        try {
            $this->application->getContainer();
            $this->assertTrue(false);
        } catch (RuntimeException $exception) {
            $this->assertTrue(true);
        }
        $this->application->make();

        $container = $this->application->getContainer();

        $this->assertEquals('test.value', $container->getParameter('test.parameter'));
        $this->assertEquals('test.child.value', $container->getParameter('test.child.parameter'));
        $this->assertEquals('overwritten.value', $container->getParameter('overwritten.parameter'));
    }

    /**
     * Test container service
     */
    public function testContainerServices()
    {
        try {
            $this->application->getContainer();
            $this->assertTrue(false);
        } catch (RuntimeException $exception) {
            $this->assertTrue(true);
        }
        $this->application->make();

        $container = $this->application->getContainer();

        $this->assertTrue($container->get('test.child.service') instanceof \stdClass);
        $this->assertTrue($container->get('test.service') instanceof \stdClass);
        $this->assertTrue($container->get('overwritten.service') instanceof \stdClass);
    }

    /**
     * Test default injection rules
     */
    public function testDefaultInjectionRules()
    {
        $this->application->make();

        $container = $this->application->getContainer();

        $this->assertTrue(
            $container->get('trait.injection.service')->getContainer() instanceof ApplicationContainerBuilder
        );
        $this->assertTrue(
            $container->get('interface.injection.service')->getContainer() instanceof ApplicationContainerBuilder
        );
    }
}
