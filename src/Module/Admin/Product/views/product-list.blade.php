<?php

/**
 * Global variables
 * --------------------------------------------------------------
 * @var  $app       AppContext      Application context.
 * @var  $vm        ProductListView The view model object.
 * @var  $uri       SystemUri       System Uri information.
 * @var  $chronos   ChronosService  The chronos datetime service.
 * @var  $nav       Navigator       Navigator object to build route.
 * @var  $asset     AssetService    The Asset manage service.
 * @var  $lang      LangService     The language translation service.
 */

declare(strict_types=1);

use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;
use App\Module\Admin\Product\ProductListView;

/**
 * @var \App\Entity\Product $entity
 */

$workflow = $app->service(\Unicorn\Workflow\BasicStateWorkflow::class);
?>

@extends('admin.global.body-list')

@section('toolbar-buttons')
    @include('list-toolbar')
@stop

@section('content')
    <form id="admin-form" action="" x-data="{ grid: $store.grid }"
        x-ref="gridForm"
        data-ordering="{{ $ordering }}"
        method="post">

        <x-filter-bar :form="$form" :open="$showFilters"></x-filter-bar>

        @if (count($items))
        {{-- RESPONSIVE TABLE DESC --}}
        <p class="d-sm-block d-md-none">
            @lang('unicorn.grid.responsive.table.desc')
        </p>

        <div class="grid-table table-lg-responsive">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th style="width: 1%">
                        <x-toggle-all></x-toggle-all>
                    </th>
                    <th style="width: 5%" class="text-nowrap">
                        <x-sort field="product.state">
                            @lang('unicorn.field.state')
                        </x-sort>
                    </th>
                    <th>
                        <x-sort field="product.featured">
                            Feature
                        </x-sort>
                    </th>
                    <th class="text-nowrap">
                        <x-sort field="product.title">
                            @lang('unicorn.field.title')
                        </x-sort>
                    </th>
                    <th>
                        <x-sort field="product.category_id">
                            Category
                        </x-sort>
                    </th>
                    <th>
                        <x-sort field="product.created_by">
                            Author
                        </x-sort>
                    </th>
                    <th style="width: 10%" class="text-nowrap">
                        <div class="d-flex w-100 justify-content-end">
                            <x-sort
                                asc="product.ordering ASC"
                                desc="product.ordering DESC"
                            >
                                @lang('unicorn.field.ordering')
                            </x-sort>
                            @if($vm->reorderEnabled($ordering))
                                <x-save-order class="ml-2 ms-2"></x-save-order>
                            @endif
                        </div>
                    </th>
                    <th style="width: 1%" class="text-nowrap">
                        @lang('unicorn.field.delete')
                    </th>
                    <th style="width: 1%" class="text-nowrap text-right text-end">
                        <x-sort field="product.id">
                            @lang('unicorn.field.id')
                        </x-sort>
                    </th>
                </tr>
                </thead>

                <tbody>
                @foreach($items as $i => $item)
                    <?php
                        $entity = $vm->prepareItem($item);
                    ?>
                    <tr>
                        <td>
                            <x-row-checkbox :row="$i" :id="$entity->getId()"></x-row-checkbox>
                        </td>
                        <td>
                            <x-state-dropdown color-on="text"
                                button-style="width: 100%"
                                use-states
                                :workflow="$workflow"
                                :id="$entity->getId()"
                                :value="$item->state"
                            />
                        </td>
                        <td>
                            @if ($entity->isFeatured())
                            <button class="btn btn-sm btn-light"
                                data-bs-toggle="tooltip"
                                title="精選: 點擊取消"
                                @click="grid.doTask('unfeature', '{{ $entity->getId() }}')"
                            >
                                <span class="fas fa-star"></span>
                            </button>
                            @else
                            <button class="btn btn-sm btn-light"
                                data-bs-toggle="tooltip"
                                title="點擊設為精選"
                                @click="grid.doTask('feature', '{{ $entity->getId() }}')"
                            >
                                <span class="far fa-star"></span>
                            </button>
                            @endif
                        </td>
                        <td>
                            <div>
                                <a href="{{ $nav->to('product_edit')->id($entity->getId()) }}">
                                    {{ $item->title }}
                                </a>
                            </div>
                        </td>
                        <td>
                            {{ $item->category->title ?? '-' }}
                        </td>
                        <td>
                            {{ $item->author->name ?? '-' }}
                        </td>
                        <td class="text-end text-right">
                            <x-order-control
                                :enabled="$vm->reorderEnabled($ordering)"
                                :row="$i"
                                :id="$entity->getId()"
                                :value="$item->ordering"
                            ></x-order-control>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                @click="grid.deleteItem('{{ $entity->getId() }}')"
                                data-dos
                            >
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                        <td class="text-right text-end">
                            {{ $entity->getId() }}
                        </td>
                    </tr>
                @endforeach
                </tbody>

                <tfoot>
                <tr>
                    <td colspan="20">
                        {!! $pagination->render() !!}
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        @else
            <div class="grid-no-items card bg-light" style="padding: 125px 0;">
                <div class="card-body text-center">
                    <h3 class="text-secondary">@lang('unicorn.grid.no.items')</h3>
                </div>
            </div>
        @endif

        <div class="d-none">
            <input name="_method" type="hidden" value="PUT" />
            @include('@csrf')
        </div>

        <x-batch-modal :form="$form" namespace="batch"></x-batch-modal>
    </form>

@stop
