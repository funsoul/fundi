<?php

declare(strict_types=1);

namespace FunsoulTest\FunDi\Stub;

use FunsoulTest\FunDi\Stub\Tools\ToolsInterface;

/**
 * Class Travel
 *
 * @package FunsoulTest\FunDi\Stub
 */
class Travel
{
    /** @var ToolsInterface */
    private $tools;

    public function __construct(ToolsInterface $tools)
    {
        $this->tools = $tools;
    }

    public function go() : string
    {
        return $this->tools->go();
    }
}