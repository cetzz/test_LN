<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

    $app->get('[/]', function (Request $request, Response $response) use ($app) {
        $routes = $app->getContainer()->router->getRoutes();
        $response=array();
        $params =$request->getQueryParams();
        foreach ($routes as $route) {
            $response[str_replace('}','',str_replace('{','',str_replace(']','',str_replace('[','',$route->getPattern()))))]=$route->getPattern();
        }
        array_shift($response);

        return wookieEncode($params,json_encode($response));
    });
    $app->group('/films', function () use ($app) {
        $app->get('[/]', function (Request $request, Response $response) {
            $params =$request->getQueryParams();
            $page=(isset($params['page']) && $params['page']!=''?$params['page']:1);
            $search=(isset($params['search']) && $params['search']!=''?$params['search']:null);
            
            return wookieEncode($params,getFilms($page,$search));
        });
    
        $app->get('/{id}[/]', function (Request $request, Response $response, $args) {
            $params =$request->getQueryParams();

            return wookieEncode($params,searchFilmByID($args['id']));
        });
    });

    $app->group('/starships', function () use ($app) {
        $app->get('[/]', function (Request $request, Response $response) {
            $params =$request->getQueryParams();
            $page=(isset($params['page']) && $params['page']!=''?$params['page']:1);
            $search=(isset($params['search']) && $params['search']!=''?$params['search']:null);
            
            return wookieEncode($params,getStarships($page,$search));
        });

        $app->get('/{id}[/]', function (Request $request, Response $response, $args) {
            $params =$request->getQueryParams();

            return wookieEncode($params,searchStarshipByID($args['id']));
        });

        $app->group('/amount', function () use ($app) {
            $app->get('/get[/]', function (Request $request, Response $response, $args) {
                $params =$request->getQueryParams();
                $id=(isset($params['id']) && $params['id']!=''?$params['id']:0);

                return wookieEncode($params,searchStarshipAmountByID($id));
            });
            $app->get('/set[/]', function (Request $request, Response $response, $args) {
                $params =$request->getQueryParams();
                $id=(isset($params['id']) && $params['id']!=''?$params['id']:0);
                $amount=(isset($params['amount']) && $params['amount']!=''?$params['amount']:null);

                return wookieEncode($params,setStarshipAmountByID($id,$amount));
            });
            $app->get('/increase[/]', function (Request $request, Response $response, $args) {
                $params =$request->getQueryParams();
                $id=(isset($params['id']) && $params['id']!=''?$params['id']:0);
                $amount=(isset($params['amount']) && $params['amount']!=''?$params['amount']:null);

                return wookieEncode($params,increaseStarshipAmountByID($id,$amount));
            });
            $app->get('/decrease[/]', function (Request $request, Response $response, $args) {
                $params =$request->getQueryParams();
                $id=(isset($params['id']) && $params['id']!=''?$params['id']:0);
                $amount=(isset($params['amount']) && $params['amount']!=''?$params['amount']:null);

                return wookieEncode($params,decreaseStarshipAmountByID($id,$amount));
            });
        });
    });


    $app->group('/vehicles', function () use ($app) { 
        $app->get('[/]', function (Request $request, Response $response) {
            $params =$request->getQueryParams();
            $page=(isset($params['page']) && $params['page']!=''?$params['page']:1);
            $search=(isset($params['search']) && $params['search']!=''?$params['search']:null);

            return wookieEncode($params,getVehicles($page,$search));
        });

        $app->get('/{id}[/]', function (Request $request, Response $response, $args) {
            $params =$request->getQueryParams();

            return wookieEncode($params,searchVehicleByID($args['id']));
        });

        $app->group('/amount', function () use ($app) {
            $app->get('/get[/]', function (Request $request, Response $response, $args) {
                $params =$request->getQueryParams();
                $id=(isset($params['id']) && $params['id']!=''?$params['id']:0);

                return wookieEncode($params,searchVehicleAmountByID($id));
            });
            $app->get('/set[/]', function (Request $request, Response $response, $args) {
                $params =$request->getQueryParams();
                $id=(isset($params['id']) && $params['id']!=''?$params['id']:0);
                $amount=(isset($params['amount']) && $params['amount']!=''?$params['amount']:null);

                return wookieEncode($params,setVehicleAmountByID($id,$amount));
            });
            $app->get('/increase[/]', function (Request $request, Response $response, $args) {
                $params =$request->getQueryParams();
                $id=(isset($params['id']) && $params['id']!=''?$params['id']:0);
                $amount=(isset($params['amount']) && $params['amount']!=''?$params['amount']:null);

                return wookieEncode($params,increaseVehicleAmountByID($id,$amount));
            });
            $app->get('/decrease[/]', function (Request $request, Response $response, $args) {
                $params =$request->getQueryParams();
                $id=(isset($params['id']) && $params['id']!=''?$params['id']:0);
                $amount=(isset($params['amount']) && $params['amount']!=''?$params['amount']:null);

                return wookieEncode($params,decreaseVehicleAmountByID($id,$amount));
            });
        });
    });

    $app->get('/init[/]', function (Request $request, Response $response) {
        $params =$request->getQueryParams();

        return wookieEncode($params,initializeDatabase($params));
    });

    ?>
