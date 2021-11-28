<?php

declare(strict_types=1);

/**
 * Part of phoenix project.
 *
 * @copyright  Copyright {C} 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

use Lyrasoft\Luna\Module\Admin\Category\CategoryController;
use Lyrasoft\Luna\Module\Admin\Category\CategoryEditView;
use Lyrasoft\Luna\Module\Admin\Category\CategoryListView;
use Unicorn\Middleware\KeepUrlQueryMiddleware;
use Windwalker\Core\Router\RouteCreator;

/** @var RouteCreator $router */

$router->group('category')
    ->middleware(KeepUrlQueryMiddleware::di(options: ['key' => 'type',]))
    ->register(
        function (RouteCreator $router) {
            // Category
            $router->any('category_edit', '/category/edit/{type}[/{id}]')
                ->controller(CategoryController::class)
                ->view(CategoryEditView::class)
                ->extra('layout', 'country');

            // Categories
            $router->any('category_list', '/category/list/{type}')
                ->controller(CategoryController::class)
                ->postHandler('copy')
                ->patchHandler('batch')
                ->putHandler('filter')
                ->deleteHandler('delete')
                ->view(CategoryListView::class);

            // Ajax Category List
            $router->any('category_ajax_list', '/category/ajax/list/{type}')
                ->controller(CategoryController::class, 'ajaxList');
        }
    );
