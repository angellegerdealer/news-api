<?php

declare(strict_types=1);

namespace Niden\Providers;


use Niden\Api\Controllers\Users\GetController as UsersGetController;
use Niden\Api\Controllers\Users\AddController as UsersAddController;
use Niden\Api\Controllers\News\UpdateController as NewsUpdateController;
use Niden\Api\Controllers\News\GetController as NewsGetController;
use Niden\Api\Controllers\News\AddController as NewsAddController;
use Niden\Api\Controllers\News\DeleteController as NewsDeleteController;
use Niden\Api\Controllers\LoginController;
use Niden\Constants\Relationships as Rel;
use Niden\Middleware\NotFoundMiddleware;
use Niden\Middleware\AuthenticationMiddleware;
use Niden\Middleware\ResponseMiddleware;
use Niden\Middleware\TokenUserMiddleware;
use Niden\Middleware\TokenValidationMiddleware;
use Niden\Middleware\TokenVerificationMiddleware;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;
use Phalcon\Events\Manager;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\Collection;

class RouterProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     *
     * @param DiInterface $container
     */
    public function register(DiInterface $container)
    {
        /** @var Micro $application */
        $application   = $container->getShared('application');
        /** @var Manager $eventsManager */
        $eventsManager = $container->getShared('eventsManager');

        $this->attachRoutes($application);
        $this->attachMiddleware($application, $eventsManager);

        $application->setEventsManager($eventsManager);
    }

    /**
     * Attaches the middleware to the application
     *
     * @param Micro   $application
     * @param Manager $eventsManager
     */
    private function attachMiddleware(Micro $application, Manager $eventsManager)
    {
        $middleware = $this->getMiddleware();

        /**
         * Get the events manager and attach the middleware to it
         */
        foreach ($middleware as $class => $function) {
            $eventsManager->attach('micro', new $class());
            $application->{$function}(new $class());
        }
    }

    /**
     * Attaches the routes to the application; lazy loaded
     *
     * @param Micro $application
     */
    private function attachRoutes(Micro $application)
    {
        $routes = $this->getRoutes();

        foreach ($routes as $route) {
            $collection = new Collection();
            $collection
                ->setHandler($route[0], true)
                ->setPrefix($route[1])
                ->{$route[2]}($route[3], 'callAction');

            $application->mount($collection);
        }
    }

    /**
     * Returns the array for the middleware with the action to attach
     *
     * @return array
     */
    private function getMiddleware(): array
    {
        return [
            NotFoundMiddleware::class          => 'before',
            AuthenticationMiddleware::class    => 'before',
            TokenUserMiddleware::class         => 'before',
            TokenVerificationMiddleware::class => 'before',
            TokenValidationMiddleware::class   => 'before',
            ResponseMiddleware::class          => 'after',
        ];
    }

    /**
     * Returns the array for the routes
     *
     * @return array
     */
    private function getRoutes(): array
    {
        $routes = [
            // Class, Method, Route, Handler
            [LoginController::class,        '/login',     'post', '/'],
            [UsersAddController::class,     '/users',     'post', '/'],
            [UsersGetController::class,     '/users',     'get',  '/'],
            [UsersGetController::class,     '/users',     'get',  '/{recordId:[0-9]+}'],
            [NewsAddController::class,      '/news',      'post', '/'],
            [NewsUpdateController::class,   '/news',      'put', '/{recordId:[0-9]+}'],
            [NewsGetController::class,      '/news',      'get',  '/{recordId:[0-9]+}'],
            [NewsDeleteController::class,   '/news',      'delete',  '/{recordId:[0-9]+}'],
        ];

          $routes = $this->getMultiRoutes($routes, NewsGetController::class, Rel::NEWS);



        return $routes;
    }

    /**
     * Adds multiple routes for the same handler abiding by the JSONAPI standard
     *
     * @param array  $routes
     * @param string $class
     * @param string $relationship
     *
     * @return array
     */
    private function getMultiRoutes(array $routes, string $class, string $relationship): array
    {
        $routes[] = [$class, '/' . $relationship, 'get', '/'];
        $routes[] = [$class, '/' . $relationship, 'get', '/{recordId:[0-9]+}'];
        $routes[] = [$class, '/' . $relationship, 'get', '/{recordId:[0-9]+}/{relationships:[a-zA-Z-,.]+}'];
        $routes[] = [$class, '/' . $relationship, 'get', '/{recordId:[0-9]+}/relationships/{relationships:[a-zA-Z-,.]+}'];

        return $routes;
    }
}
