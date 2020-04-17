<?php

namespace FreeElephants\JsonApiToolkit\Routing\FastRoute;

use FastRoute\Dispatcher\GroupCountBased;

class OptionableGroupCountBasedDispatcher extends GroupCountBased
{
    public function dispatch($httpMethod, $uri)
    {
        $dispatched = parent::dispatch($httpMethod, $uri);

        $methods = [];
        foreach ($this->staticRouteMap as $method => $data) {
            if ($this->pathHasMethod($uri, $method)) {
                $methods[] = $method;
            }
        }
        $dispatched[2] = array_merge($dispatched[2], $methods);

        return $dispatched;
    }

    private function pathHasMethod( $path, string $method)
    {
        $srm = $this->staticRouteMap;
        $vrd = $this->variableRouteData;
        return array_key_exists($path, isset($srm[$method]) ? $srm[$method] : [])
            || array_key_exists($path, isset($vrd[$method]) ? $vrd[$method] : []);
    }
}