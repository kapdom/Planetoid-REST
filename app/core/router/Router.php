<?php
/**
 * Created by PhpStorm.
 * User: DOOMinikP
 * Date: 2018-07-23
 * Time: 08:19
 */

namespace App\Core\Router;

use App\Core\Request\Request;
use App\Core\Services\LogService;
use App\Core\View\View;

class Router
{
    /**
     * @var string input url
     */
    public static $url = '';

    /**
     * @var array registered GET routers
     */
    protected static $getRoutes = [];

    /**
     * @var array registered POST routers
     */
    protected static $postRoutes = [];

    /**
     * @var array registered PUT routers
     */
    protected static $putRoutes = [];

    /**
     * @var array registered DELETE routers
     */
    protected static $deleteRoutes = [];

    /**
     * @var string Controller Namespace
     */
    private static $controllerNamespace = 'App\Http\Controllers';

    /**
     * @var array Current registered router matched to input url
     */
    private static $matchedRoute = [];

    /**
     * Register GET route
     * @param array $routeData
     *
     * @return void
     */
    public static function get(array $routeData=[]): void
    {
        self::$getRoutes[] = self::parseRouter($routeData);
    }

    /**
     * Register POST route
     * @param array $routeData
     * @return void
     */
    public static function post(array $routeData=[]): void
    {
        self::$postRoutes[] = self::parseRouter($routeData);
    }

    /**
     * Register PUT route
     * @param array $routeData
     * @return void
     */
    public static function put(array $routeData=[]): void
    {
        self::$putRoutes[] = self::parseRouter($routeData);
    }

    /**
     * Register POST route
     * @param array $routeData
     * @return void
     */
    public static function delete(array $routeData=[]): void
    {
        self::$deleteRoutes[] = self::parseRouter($routeData);
    }

    /**
     * Create new controller object
     *
     * @param string $name
     * @param Request $request
     * @return object
     */
    public static function loadClass(string $name, Request $request): object
    {
        $class = self::$controllerNamespace.'\\'.$name;
        return new $class($request);
    }

    /**
     * Parse received route data as associated array
     * @param array $data array
     * @return array
     */
    private static function parseRouter(array $data): array
    {
        try {
            $classCall = explode('@', $data[1]);
            return ['uri' => $data[0], 'controller' => $classCall[0], 'method' => $classCall[1]];
        }catch(\Exception $e)
        {
            LogService::addLog(3,$e->getMessage());
            View::abort(400,'Bad route');
            die();
        }
    }

    /**
     * Search for registered route base on input url
     * @param array $routes routes in current request method
     * @return array|null
     */
    private static function checkRegisteredRouters(array $routes): ?array
    {
        foreach ($routes as $route){
            $uri = str_replace(['/','?'],['\/','[0-9a-zA-Z]*'],$route['uri']);
            $pattern = "/^$uri$/i";

            if(preg_match($pattern, self::$url)){
                return $route;
            };
        }
        return null;
    }

    /**
     * Check input url
     */
    public static function matchUrl(): void
    {
        try {
            self::$url = trim($_SERVER['REQUEST_URI'], '/');
            self::$matchedRoute = self::checkRegisteredRouters(self::requestType());

            if (self::$matchedRoute != null) {
                self::launchController();
            } else {
                View::abort(404, 'No Controller');
            }
        }catch (\Exception $e){
            LogService::addLog(3, $e->getMessage());
        }
    }

    /**
     * Launch controller and pass arguments to selected controller method
     */
    private static function launchController(): void
    {
        try {
            $request = new Request();
            $method = self::$matchedRoute['method'];
            $class = self::loadClass(self::$matchedRoute['controller'], $request);
            $methodParameters = $request->parseGetData(self::$matchedRoute['uri']);
            $class->$method(...$methodParameters);
        }catch (\Exception $e){
            LogService::addLog(1,$e->getMessage());
            View::abort(400,'Bad query');
        }
    }

    /**
     *  Load proper routes collection base on request method
     * @return array
     * @throws \Exception
     */
    private static function requestType(): array
    {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
               return self::$getRoutes;
                break;
            case 'POST':
                return self::$postRoutes;
                break;
            case 'PUT':
                return self::$putRoutes;
                break;
            case 'DELETE':
                return self::$deleteRoutes;
                break;
            default:
               throw new \Exception('Wrong Request method');
        }
    }



}