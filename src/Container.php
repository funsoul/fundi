<?php

declare(strict_types=1);

namespace Funsoul\FunDi;

use Closure;
use ReflectionClass;
use ReflectionParameter;
use ReflectionException;
use Psr\Container\ContainerInterface;
use Funsoul\FunDi\Exception\NotFoundException;
use Funsoul\FunDi\Exception\ContainerException;

/**
 * Class Container
 *
 * @package Funsoul\FunDi
 */
class Container implements ContainerInterface
{
    /** @var array */
    public $binding = [];

    /**
     * @param string $id
     *
     * @return mixed|object
     *
     * @throws ContainerException
     * @throws NotFoundException
     * @throws ReflectionException
     */
    public function get($id)
    {
        if (!isset($this->binding[$id])) {
            throw new NotFoundException();
        }

        return $this->make($id);
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function has($id)
    {
        return isset($this->binding[$id]);
    }

    /**
     * @param $abstract
     * @param null $concrete
     * @param bool $shared
     */
    public function bind($abstract, $concrete = null, $shared = false)
    {
        if (!$concrete instanceof Closure) {
            $concrete = $this->getClosure($abstract, $concrete);
        }

        $this->binding[$abstract] = compact('concrete', 'shared');
    }


    /**
     * @param $abstract
     * @param $concrete
     *
     * @return Closure
     */
    protected function getClosure($abstract, $concrete)
    {
        return function ($c) use ($abstract, $concrete) {
            $method = ($abstract == $concrete) ? 'build' : 'make';
            return $c->$method($concrete);
        };
    }

    /**
     * @param $abstract
     * @return mixed|object
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function make($abstract)
    {
        $concrete = $this->getConcrete($abstract);

        if ($this->isBuildable($concrete, $abstract)) {
            $object = $this->build($concrete);
        } else {
            $object = $this->make($concrete);
        }

        return $object;
    }

    /**
     * @param $concrete
     * @param $abstract
     *
     * @return bool
     */
    public function isBuildable($concrete, $abstract)
    {
        return $concrete === $abstract || $concrete instanceof Closure;
    }

    /**
     * @param $abstract
     *
     * @return mixed
     */
    protected function getConcrete($abstract)
    {
        if (!isset($this->binding[$abstract])) {
            return $abstract;
        }

        return $this->binding[$abstract]['concrete'];
    }


    /**
     * @param $concrete
     *
     * @return mixed|object
     *
     * @throws ContainerException
     * @throws ReflectionException
     */
    private function build($concrete) {

        if($concrete instanceof Closure) {
            return $concrete($this);
        }

        $reflector = new ReflectionClass($concrete);
        if(!$reflector->isInstantiable()) {
            throw new ContainerException();
        }

        $constructor = $reflector->getConstructor();

        if(is_null($constructor)) {
            return new $concrete;
        }

        $dependencies = $constructor->getParameters();
        $instances = $this->getDependencies($dependencies);

        return $reflector->newInstanceArgs($instances);
    }

    /**
     * @param $parameters
     *
     * @return array
     *
     * @throws ContainerException
     * @throws ReflectionException
     */
    protected function getDependencies($parameters) {
        $dependencies = [];

        /** @var ReflectionParameter $parameter */
        foreach ($parameters as $parameter) {
            $dep = $parameter->getClass();
            if (is_null($dep)) {
                $dependencies = null;
            } else {
                $dependencies[] = $this->resolveClass($parameter);
            }
        }
        return (array)$dependencies;
    }

    /**
     * @param ReflectionParameter $parameter
     *
     * @return mixed|object
     *
     * @throws ContainerException
     * @throws ReflectionException
     */
    protected function resolveClass(ReflectionParameter $parameter) {
        $name = $parameter->getClass()->name;

        return $this->make($name);
    }
}