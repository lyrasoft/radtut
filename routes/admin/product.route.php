<?php

namespace App\Routes;

use App\Module\Admin\Product\ProductController;
use App\Module\Admin\Product\ProductEditView;
use App\Module\Admin\Product\ProductListView;
use Windwalker\Core\Router\RouteCreator;

/** @var  RouteCreator $router */

$router->group('product')
    ->register(function (RouteCreator $router) {
        $router->any('product_list', '/product/list')
            ->controller(ProductController::class)
            ->view(ProductListView::class)
            ->postHandler('copy')
            ->putHandler('filter')
            ->patchHandler('batch');

        $router->any('product_edit', '/product/edit[/{id}]')
            ->controller(ProductController::class)
            ->view(ProductEditView::class);
    });
