<?php

declare(strict_types=1);

namespace FunsoulTest\FunDi;

use Funsoul\FunDi\Container;
use FunsoulTest\FunDi\Stub\Tools\Air;
use FunsoulTest\FunDi\Stub\Tools\ToolsInterface;
use FunsoulTest\FunDi\Stub\Travel;
use PHPUnit\Framework\TestCase;

/**
 * Class ContainerTest
 *
 * @package FunsoulTest\FunDi
 */
class ContainerTest extends TestCase
{
    /** @var Container */
    private $container;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->container = new Container();
        $this->container->bind(ToolsInterface::class, Air::class);
        $this->container->bind(Travel::class, Travel::class);
    }

    public function testHas()
    {
        $this->assertTrue($this->container->has(Travel::class));
    }

    public function testGet()
    {
        $air = new Air();

        /** @var Travel $travel */
        $travel = $this->container->get(Travel::class);

        $this->assertEquals($air->go(), $travel->go());
    }
}