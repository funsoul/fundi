<?php

declare(strict_types=1);

namespace Funsoul\FunDi\Exception;

use Psr\Container\ContainerExceptionInterface;
use Exception;

/**
 * Class NotFoundException
 *
 * @package Funsoul\FunDi\Exception
 */
class ContainerException extends Exception implements ContainerExceptionInterface
{

}