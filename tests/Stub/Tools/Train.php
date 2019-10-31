<?php

declare(strict_types=1);

namespace FunsoulTest\FunDi\Stub\Tools;

/**
 * Class Train
 *
 * @package FunsoulTest\FunDi\Stub\Tools
 */
class Train implements ToolsInterface
{
    public function go() : string
    {
        return "by train";
    }
}