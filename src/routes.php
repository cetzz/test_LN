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

    $app->get('/films/', function (Request $request, Response $response) {
        $params =$request->getQueryParams();
        return wookieEncode($params,getFilmsByPage((isset($params['page']) && $params['page']!=''?$params['page']:1)));
    });

    $app->get('/films/{id}/', function (Request $request, Response $response, $args) {
        $params =$request->getQueryParams();
        return wookieEncode($params,searchFilmByID($args['id']));
    });

    $app->get('/starships/', function (Request $request, Response $response) {
        $params =$request->getQueryParams();
        return wookieEncode($params,getStarshipsByPage((isset($params['page']) && $params['page']!=''?$params['page']:1)));
    });

    $app->get('/starships/{id}/', function (Request $request, Response $response, $args) {
        $params =$request->getQueryParams();
        return wookieEncode($params,searchStarshipByID($args['id']));
    });

    $app->get('/vehicles/', function (Request $request, Response $response) {
        $params =$request->getQueryParams();
        return wookieEncode($params,getVehiclesByPage((isset($params['page']) && $params['page']!=''?$params['page']:1)));
    });

    $app->get('/vehicles/{id}/', function (Request $request, Response $response, $args) {
        $params =$request->getQueryParams();
        return wookieEncode($params,searchVehicleByID($args['id']));
    });

    $app->get('/init/', function (Request $request, Response $response) {
        $params =$request->getQueryParams();
        return wookieEncode($params,initializeDatabase($params));
    });

    ?>
