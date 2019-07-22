<?php

namespace app\core;

use app\exceptions\Exception;
use app\exceptions\HttpException;
use app\exceptions\NotFoundException;
use app\services\template\TemplateRenderer;
use app\services\template\TwigRenderer;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

class Application
{
    private $_csrf;

    /**
     * @var Request
     */
    private $_request;

    /**
     * @var Route[]
     */
    private $_routes;

    /**
     * @var \PDO
     */
    private $_connection;

    /**
     * @var TemplateRenderer
     */
    private $_renderer;

    /**
     * @var EntityManager
     */
    private $_entityManager;

    /**
     * @var static
     */
    private static $_instance;

    private function __construct()
    {

    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     * @return TemplateRenderer
     */
    public function getRenderer()
    {
        return $this->_renderer;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->_entityManager;
    }

    private function _getDSN()
    {
        $dbHost = getenv('POSTGRES_HOST');
        $dbName = getenv('POSTGRES_DB');
        $dbUser = getenv('POSTGRES_USER');
        $dbPass = getenv('POSTGRES_PASSWORD');
        return [
            'host' => $dbHost,
            'name' => $dbName,
            'user' => $dbUser,
            'pass' => $dbPass
        ];
    }

    /**
     * @return \PDO
     */
    public function getConnection()
    {
        if (!isset($this->_connection)) {
            $dbConfig = $this->_getDSN();
            $this->_connection = new \PDO("pgsql:host={$dbConfig['host']};dbname={$dbConfig['name']}", $dbConfig['user'], $dbConfig['pass']);
        }

        return $this->_connection;
    }

    public static function get()
    {
        if (!isset(static::$_instance)) {
            static::$_instance = new static();
        }

        return static::$_instance;
    }

    public function addRoute($method, $path, $controller, $action)
    {

        $this->_routes[] = Route::make([
            'method' => mb_strtoupper($method),
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ]);
        return true;
    }

    /**
     * @param $request
     * @return Route|false
     */
    private function findRoute($request)
    {
        foreach ($this->_routes as $route) {
            if (($route->method === '*' || $route->method === $request->getMethod())
                && $route->match($request->getPath())) {
                return $route;
            }
        }

        return false;
    }

    /**
     * @param $code
     * @param $message
     * @param $file
     * @param $line
     * @throws Exception
     */
    public function errorHandler($code, $message, $file, $line)
    {
        throw new Exception("$message in $file on line $line", 0);
    }

    /**
     * @param Exception $e
     */
    public function exceptionHandler($e)
    {
        if ($e instanceof HttpException) {
            http_response_code($e->getStatus());
        }
        print get_class($e).": Message: {$e->getMessage()} in {$e->getFile()} on line {$e->getLine()}";
//        print_r($e->getTrace());
    }

    public function setHandlers()
    {
        set_exception_handler(array($this, 'exceptionHandler'));
        set_error_handler(array($this, 'errorHandler'));
    }

    public function initialize()
    {
        $this->_request = new Request($_REQUEST);
        $this->_renderer = new TwigRenderer(dirname(__DIR__) . '/views');
        \Doctrine\DBAL\Types\Type::addType('uuid', \Ramsey\Uuid\Doctrine\UuidType::class);
        $config = Setup::createAnnotationMetadataConfiguration([dirname(__DIR__) . '/models']);
        $dbConfig = $this->_getDSN();
        $this->_entityManager = EntityManager::create([
            'url' => "pgsql://{$dbConfig['user']}:{$dbConfig['pass']}@{$dbConfig['host']}/{$dbConfig['name']}"
        ], $config);
    }

    public function refreshCSRF($force = false)
    {
        if (empty($_SESSION['csrf']) || $force) {
            $_SESSION['csrf'] = md5(uniqid(rand(), TRUE));
        }
        $this->_csrf = $_SESSION['csrf'];
        $this->_renderer->addParams(['csrf' => $this->_csrf]);
        return $this->_csrf;
    }

    public function getCSRF()
    {
        return $this->_csrf;
    }

    public function run()
    {
        session_start();
        $this->setHandlers();
        $this->initialize();
        $this->refreshCSRF();

        require __DIR__ . '/../routes/map.php';

        $route = $this->findRoute($this->_request);
        if (!$route) {
            throw new NotFoundException("Route {$this->_request->getPath()} not found");
        }

        $controller = new $route->controller($this);
        $action = $route->action;

        $response = call_user_func([$controller, $action], $route->routeParams);
        echo $response;
    }
}
