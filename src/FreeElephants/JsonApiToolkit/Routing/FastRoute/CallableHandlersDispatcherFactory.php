<?php
//
//namespace FreeElephants\JsonApiToolkit\Routing\FastRoute;
//
//use FastRoute\Dispatcher;
//use FastRoute\RouteCollector;
//use function FastRoute\simpleDispatcher;
//
//class CallableHandlersDispatcherFactory implements DispatcherFactoryInterface
//{
//
//    public const DEFAULT_CALLBACK_CHECK_POLICY = self::ADD_IF_CALLBACK;
//    public const ADD_IF_CALLBACK = 0;
//    public const ADD_ANYWAY = 1;
//    public const THROW_EXCEPTION_IF_NOT_CALLBACK = 2;
//    private int $callbackCheckPolicy;
//
//    public function __construct(int $callbackCheckPolicy = self::DEFAULT_CALLBACK_CHECK_POLICY)
//    {
//        $this->callbackCheckPolicy = $callbackCheckPolicy;
//    }
//
//    public function buildDispatcher(array $routesMap): Dispatcher
//    {
//        $dispatcher = simpleDispatcher(function (RouteCollector $routeCollector) use ($routesMap) {
//            foreach ($routesMap as $path => $route) {
//                foreach ($route as $method => $callback) {
//                    if ($this->verifyCallback($callback)) {
//                        $routeCollector->addRoute($method, $path, $callback);
//                    }
//                }
//            }
//        });
//
//        return $dispatcher;
//    }
//
//    private function verifyCallback($callback): bool
//    {
//        if (is_callable($callback) || $this->callbackCheckPolicy === self::ADD_ANYWAY) {
//            return true;
//        } elseif ($this->callbackCheckPolicy === self::THROW_EXCEPTION_IF_NOT_CALLBACK) {
//            throw new \InvalidArgumentException();
//        }
//        return false;
//    }
//}