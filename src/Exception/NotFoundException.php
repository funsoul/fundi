<?php

declare(strict_types=1);

namespace Funsoul\FunDi\Exception;

use Psr\Container\NotFoundExceptionInterface;
use Exception;

/**
 * Class NotFoundException
 *
 * @package Funsoul\FunDi\Exception
 */
class NotFoundException extends Exception implements NotFoundExceptionInterface
{

}