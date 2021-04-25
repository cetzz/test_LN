<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

    $app->get('/', function (Request $request, Response $response) use ($app) {
        $routes = $app->getContainer()->router->getRoutes();
        $response=array();
        $params =$request->getQueryParams();
        foreach ($routes as $route) {
            $response[str_replace('/','',$route->getPattern())]=$route->getPattern();
        }
        array_shift($response);
        return wookieEncode($params,json_encode($response));
    });
    $app->group('/films', function () use ($app) {
        $app->get('[/]', function (Request $request, Response $response) {
            $params =$request->getQueryParams();
            return wookieEncode($params,getFilms((isset($params['page']) && $params['page']!=''?$params['page']:1),(isset($params['search']) && $params['search']!=''?$params['search']:null)));
        });
    
        $app->get('/{id}[/]', function (Request $request, Response $response, $args) {
            $params =$request->getQueryParams();
            return wookieEncode($params,searchFilmByID($args['id']));
        });
    });

    $app->group('/starships', function () use ($app) {
        $app->get('[/]', function (Request $request, Response $response) {
            $params =$request->getQueryParams();
            return wookieEncode($params,getStarships((isset($params['page']) && $params['page']!=''?$params['page']:1),(isset($params['search']) && $params['search']!=''?$params['search']:null)));
        });

        $app->get('/{id}[/]', function (Request $request, Response $response, $args) {
            $params =$request->getQueryParams();
            return wookieEncode($params,searchStarshipByID($args['id']));
        });

        $app->group('/amount', function () use ($app) {
            $app->get('/get[/]', function (Request $request, Response $response, $args) {
                $params =$request->getQueryParams();
                return wookieEncode($params,searchStarshipAmountByID((isset($params['id']) && $params['id']!=''?$params['id']:0)));
            });
            $app->get('/increase[/]', function (Request $request, Response $response, $args) {
                $params =$request->getQueryParams();
                return wookieEncode($params,increaseStarshipAmountByID((isset($params['id']) && $params['id']!=''?$params['id']:0),(isset($params['amount']) && $params['amount']!=''?$params['amount']:null)));
            });
            $app->get('/decrease[/]', function (Request $request, Response $response, $args) {
                $params =$request->getQueryParams();
                return wookieEncode($params,decreaseStarshipAmountByID((isset($params['id']) && $params['id']!=''?$params['id']:0),(isset($params['amount']) && $params['amount']!=''?$params['amount']:null)));
            });
        });
    });


    $app->group('/vehicles', function () use ($app) { 
        $app->get('[/]', function (Request $request, Response $response) {
            $params =$request->getQueryParams();
            return wookieEncode($params,getVehicles((isset($params['page']) && $params['page']!=''?$params['page']:1),(isset($params['search']) && $params['search']!=''?$params['search']:null)));
        });

        $app->get('/{id}[/]', function (Request $request, Response $response, $args) {
            $params =$request->getQueryParams();
            return wookieEncode($params,searchVehicleByID($args['id']));
        });

        $app->group('/amount', function () use ($app) {
            $app->get('/get[/]', function (Request $request, Response $response, $args) {
                $params =$request->getQueryParams();
                return wookieEncode($params,searchVehicleAmountByID((isset($params['id']) && $params['id']!=''?$params['id']:0)));
            });
            $app->get('/increase[/]', function (Request $request, Response $response, $args) {
                $params =$request->getQueryParams();
                return wookieEncode($params,increaseVehicleAmountByID((isset($params['id']) && $params['id']!=''?$params['id']:0),(isset($params['amount']) && $params['amount']!=''?$params['amount']:null)));
            });
            $app->get('/decrease[/]', function (Request $request, Response $response, $args) {
                $params =$request->getQueryParams();
                return wookieEncode($params,decreaseVehicleAmountByID((isset($params['id']) && $params['id']!=''?$params['id']:0),(isset($params['amount']) && $params['amount']!=''?$params['amount']:null)));
            });
        });
    });

    $app->get('/init/', function (Request $request, Response $response) {
        $params =$request->getQueryParams();
        return wookieEncode($params,initializeDatabase($params));
    });

    ?>
