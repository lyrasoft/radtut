<?php

/**
 * Part of starter project.
 *
 * @copyright      Copyright (C) 2021 __ORGANIZATION__.
 * @license        __LICENSE__
 */

declare(strict_types=1);

namespace App\Module\Admin\Dashboard;

use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\ViewModel;
use Windwalker\Core\View\View;
use Windwalker\Core\View\ViewModelInterface;
use Windwalker\ORM\ORM;

/**
 * The DashboardView class.
 */
#[ViewModel(
    layout: 'dashboard',
    js: 'dashboard.js'
)]
class DashboardView implements ViewModelInterface
{
    /**
     * DashboardView constructor.
     */
    public function __construct(protected ORM $orm)
    {
        //
    }

    /**
     * Prepare View.
     *
     * @param  AppContext  $app   The web app context.
     * @param  View        $view  The view object.
     *
     * @return    mixed
     */
    public function prepare(AppContext $app, View $view): array
    {
        $view->setTitle('Dashboard');

        return [];
    }
}
