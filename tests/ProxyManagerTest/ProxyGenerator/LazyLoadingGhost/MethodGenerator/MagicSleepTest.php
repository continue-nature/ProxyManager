<?php

declare(strict_types=1);

namespace ProxyManagerTest\ProxyGenerator\LazyLoadingGhost\MethodGenerator;

use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use ProxyManager\ProxyGenerator\LazyLoadingGhost\MethodGenerator\MagicSleep;
use ProxyManagerTestAsset\EmptyClass;
use ReflectionClass;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\PropertyGenerator;

/**
 * Tests for {@see \ProxyManager\ProxyGenerator\LazyLoadingGhost\MethodGenerator\MagicSleep}
 *
 * @group Coverage
 */
class MagicSleepTest extends TestCase
{
    /**
     * @covers \ProxyManager\ProxyGenerator\LazyLoadingGhost\MethodGenerator\MagicSleep::__construct
     */
    public function testBodyStructure() : void
    {
        $reflection = new ReflectionClass(EmptyClass::class);
        /** @var PropertyGenerator|PHPUnit_Framework_MockObject_MockObject $initializer */
        $initializer = $this->createMock(PropertyGenerator::class);
        /** @var MethodGenerator|PHPUnit_Framework_MockObject_MockObject $initMethod */
        $initMethod = $this->createMock(MethodGenerator::class);

        $initializer->expects(self::any())->method('getName')->will(self::returnValue('foo'));
        $initMethod->expects(self::any())->method('getName')->will(self::returnValue('bar'));

        $magicSleep = new MagicSleep($reflection, $initializer, $initMethod);

        self::assertSame('__sleep', $magicSleep->getName());
        self::assertCount(0, $magicSleep->getParameters());
        self::assertSame(
            "\$this->foo && \$this->bar('__sleep', []);"
            . "\n\nreturn array_keys((array) \$this);",
            $magicSleep->getBody()
        );
    }
}
