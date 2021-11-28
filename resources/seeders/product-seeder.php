<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Seeder;

use App\Entity\Product;
use Lyrasoft\Luna\Entity\Category;
use Lyrasoft\Luna\Entity\User;
use Windwalker\Core\Seed\Seeder;
use Windwalker\Database\DatabaseAdapter;
use Windwalker\ORM\EntityMapper;
use Windwalker\ORM\ORM;

/**
 * Product Seeder
 *
 * @var Seeder          $seeder
 * @var ORM             $orm
 * @var DatabaseAdapter $db
 */
$seeder->import(
    static function () use ($seeder, $orm, $db) {
        $faker = $seeder->faker('en_US');

        /** @var EntityMapper<Product> $mapper */
        $mapper = $orm->mapper(Product::class);

        // 取得 user, category ids, 隨機插入 product 記錄內
        $userIds = $orm->findColumn(User::class, 'id')->dump();
        $categoryIds = $orm->findColumn(Category::class, 'id', ['type' => 'product'])->dump();

        foreach (range(1, 150) as $i) {
            $item = $mapper->createEntity();

            $item->setTitle($faker->sentence(3));
            $item->setCategoryId((int) $faker->randomElement($categoryIds));
            $item->setIntro($faker->paragraph(10));
            $item->setDescription($faker->paragraph(50));
            $item->setCover($faker->unsplashImage(800, 800));
            $item->setImages(
                array_map(
                    fn ($img) => ['url' => $img, 'title' => ''],
                    $faker->unsplashImages(6, 800, 800)
                )
            );
            $item->setPrice(random_int(500, 9999));
            $item->setInventory(random_int(0, 1000));
            $item->setState($faker->randomElement([1, 1, 0]));
            $item->setCreated($created = $faker->dateTimeThisYear());
            $item->setModified($created->modify('+10days'));
            $item->setCreatedBy((int) $faker->randomElement($userIds));
            $item->setModifiedBy((int) $faker->randomElement($categoryIds));

            $item = $mapper->createOne($item);

            $seeder->outCounting();
        }
    }
);

$seeder->clear(
    static function () use ($seeder, $orm, $db) {
        $seeder->truncate(Product::class);
    }
);
