<?php

/**
 * Part of starter project.
 *
 * @copyright    Copyright (C) 2021 __ORGANIZATION__.
 * @license        __LICENSE__
 */

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product;
use Lyrasoft\Luna\Entity\Category;
use Lyrasoft\Luna\Entity\User;
use Unicorn\Attributes\ConfigureAction;
use Unicorn\Attributes\Repository;
use Unicorn\Repository\Actions\BatchAction;
use Unicorn\Repository\Actions\ReorderAction;
use Unicorn\Repository\Actions\SaveAction;
use Unicorn\Repository\ListRepositoryInterface;
use Unicorn\Repository\ListRepositoryTrait;
use Unicorn\Repository\ManageRepositoryInterface;
use Unicorn\Repository\ManageRepositoryTrait;
use Unicorn\Selector\ListSelector;
use Windwalker\ORM\SelectorQuery;
use Windwalker\Query\Query;

/**
 * The ProductRepository class.
 */
#[Repository(entityClass: Product::class)]
class ProductRepository implements ManageRepositoryInterface, ListRepositoryInterface
{
    use ManageRepositoryTrait;
    use ListRepositoryTrait;

    public function getListSelector(): ListSelector
    {
        $selector = $this->createSelector();

        $selector->addAllowFields(
            'start_date',
            'end_date'
        );

        $selector->addFilterHandler(
            'start_date',
            function (Query $query, string $field, mixed $value) {
                if ($value !== '') {
                    $query->where('product.created', '>=', $value);
                }
            }
        );

        $selector->addFilterHandler(
            'end_date',
            function (Query $query, string $field, mixed $value) {
                if ($value !== '') {
                    $query->where('product.created', '<=', $value);
                }
            }
        );

        $selector->addSearchHandler(
            'author.name',
            function (Query $query, string $field, mixed $value) {
                $query->where('author.name', 'LIKE', $value . '%');
            }
        );

        $selector->from(Product::class)
            ->leftJoin(
                Category::class,
                null,
                'category.id',
                'product.category_id'
            )
            ->leftJoin(
                User::class,
                'author',
                'author.id',
                'product.created_by'
            );

        return $selector;
    }

    #[ConfigureAction(SaveAction::class)]
    protected function configureSaveAction(SaveAction $action): void
    {
        //
    }

    #[ConfigureAction(ReorderAction::class)]
    protected function configureReorderAction(ReorderAction $action): void
    {
        //
    }

    #[ConfigureAction(BatchAction::class)]
    protected function configureBatchAction(BatchAction $action): void
    {
        //
    }
}
