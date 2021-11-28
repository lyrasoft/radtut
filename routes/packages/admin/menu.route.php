<?php

namespace App\Routes;

use Lyrasoft\Luna\Module\Admin\Menu\MenuController;
use Lyrasoft\Luna\Module\Admin\Menu\MenuEditView;
use Lyrasoft\Luna\Module\Admin\Menu\MenuListView;
use Unicorn\Middleware\KeepUrlQueryMiddleware;
use Windwalker\Core\Router\RouteCreator;

/** @var  RouteCreator $router */

$router->group('menu')
    ->middleware(
        KeepUrlQueryMiddleware::class,
        options: [
            'key' => 'type'
        ]
    )
    ->register(function (RouteCreator $router) {
        $router->any('menu_list', '/menu/list[/{type}]')
            ->controller(MenuController::class)
            ->view(MenuListView::class)
            ->postHandler('copy')
            ->putHandler('filter')
            ->patchHandler('batch');

        $router->any('menu_edit', '/menu/edit[/{id}]')
            ->controller(MenuController::class)
            ->view(MenuEditView::class);
    });
