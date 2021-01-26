<?php

namespace FreeElephants\JsonApiToolkit\Middleware;

use FreeElephants\JsonApiToolkit\Middleware\Factory\MiddlewareFactoryInterface;
use Laminas\Stratigility\MiddlewarePipe;
use Laminas\Stratigility\MiddlewarePipeInterface;
use Psr\Http\Message\ServerRequestInterface;

class MethodAwareMiddlewarePipeFactory implements MiddlewarePipeFactoryInterface
{
    private MiddlewareFactoryInterface $middlewareFactory;
    private array $middlewareMap;

    public function __construct(MiddlewareFactoryInterface $middlewareFactory, array $middlewareMap)
    {
        $this->middlewareFactory = $middlewareFactory;
        $this->middlewareMap = $middlewareMap;
    }

    /**
     * accept structure:
     * [
     *      '/path' => [
     *          [
     *              'methods' => ['*'],
     *              'middleware' => [
     *                  MiddlewareName1::class => [$middlewareInstanceOptionA, $middlewareInstanceOptionB, ...],
     *              ],
     *          ],
     *          [
     *              'methods' => [
     *                  'POST',
     *                  'PATCH',
     *              ],
     *              'middleware' => [
     *                  MiddlewareName1::class => [$middlewareInstanceOptionA, $middlewareInstanceOptionB, ...],
     *                  MiddlewareName2::class => [$middlewareInstanceOptionA, $middlewareInstanceOptionB, ...],
     *              ],
     *          ],
     *          [
     *              'methods' => [
     *                  'GET', 'HEAD',
     *              ],
     *              'middleware' => [
     *                  MiddlewareName1::class => [$middlewareInstanceOptionA, $middlewareInstanceOptionB, ...],
     *                  MiddlewareName2::class => [$middlewareInstanceOptionA, $middlewareInstanceOptionB, ...],
     *              ],
     *          ],
     *          [
     *              'methods' => [
     *                  'GET', 'HEAD',
     *              ],
     *              'middleware' => [
     *                  [
     *                      'class' => MiddlewareName1::class,
     *                      'options' => $middlewareInstanceOptionA
     *                  ],
     *                  [
     *                      'class' => MiddlewareName1::class,
     *                      'options' => $middlewareInstanceOptionA
     *                  ],
     *                  [
     *                      'class' => MiddlewareName1::class,
     *                      'options' => [$middlewareInstanceOptionA, $middlewareInstanceOptionB, ...]
     *                  ],
     *              ],
     *          ],
     *      ],
     * ]
     */
    public function create(ServerRequestInterface $request): MiddlewarePipeInterface
    {
        $pipe = new MiddlewarePipe();
        foreach ($this->middlewareMap as $path => $pathOperationsGroups) {
            foreach ($pathOperationsGroups as $group) {
                $pathMethods = $group['methods'];
                $pathMiddleware = $group['middleware'];
                foreach ($pathMiddleware as $middlewareClass => $middlewareOptions) {

                    if (is_int($middlewareClass) && is_array($middlewareOptions)) {
                        $middlewareClass = $middlewareOptions['class'];
                        $middlewareOptions = $middlewareOptions['options'] ?? [];

                    }

                    $middleware = $this->middlewareFactory->create($middlewareClass, (array)$middlewareOptions);
                    if ($pathMethods === ['*']) {
                        $decorator = new RouteParamsPathMiddlewareDecorator($path, $middleware);
                    } else {
                        $decorator = new RouteParamsPathAndMethodMiddlewareDecorator($path, $pathMethods, $middleware);
                    }
                    $pipe->pipe($decorator);
                }
            }
        }

        return $pipe;
    }
}
