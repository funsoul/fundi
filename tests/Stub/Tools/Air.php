<?php

declare(strict_types=1);

namespace FunsoulTest\FunDi\Stub\Tools;

/**
 * Class Air
 *
 * @package FunsoulTest\FunDi\Stub\Tools
 */
class Air implements ToolsInterface
{
    public function go() : string
    {
        return "by air";
    }
}