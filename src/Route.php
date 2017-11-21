<?php

/*
 * This file is part of the Lepre package.
 *
 * (c) Daniele De Nobili <danieledenobili@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Lepre\Routing;

use Lepre\Routing\Exception\UnsupportedMethodException;

/**
 * Route
 */
class Route
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var mixed
     */
    private $handler;

    /**
     * @var string[]
     */
    private $methods = [];

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private static $supportedMethods = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS', 'HEAD'];

    /**
     * @param string $path
     * @param mixed  $handler
     * @param array  $methods
     * @param string $name
     */
    public function __construct(string $path, $handler, array $methods = [], string $name = null)
    {
        $this->path = $path;
        $this->handler = $handler;
        $this->methods = $methods;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return mixed
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * @param string $method
     * @return $this
     */
    public function allowMethod(string $method): Route
    {
        if (!in_array($method, self::$supportedMethods)) {
            throw new UnsupportedMethodException($method);
        }

        $this->methods[] = $method;

        return $this;
    }

    /**
     * @param array $methods
     * @return $this
     */
    public function allowMethods(array $methods = []): Route
    {
        $this->methods = [];
        foreach ($methods as $method) {
            $this->allowMethod($method);
        }

        return $this;
    }

    /**
     * @return string[]
     */
    public function getAllowedMethods(): array
    {
        return empty($this->methods) ? self::$supportedMethods : $this->methods;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function bindName(string $name): Route
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        if ($this->name === null) {
            $this->name = $this->path;
        }

        return $this->name;
    }
}
