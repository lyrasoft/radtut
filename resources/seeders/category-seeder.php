<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 LYRASOFT.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Seeder;

use Lyrasoft\Luna\Entity\Category;
use Lyrasoft\Luna\Entity\User;
use Unicorn\Enum\BasicState;
use Unicorn\Utilities\SlugHelper;
use Windwalker\Core\Seed\Seeder;
use Windwalker\Database\DatabaseAdapter;
use Windwalker\ORM\Nested\Position;
use Windwalker\ORM\NestedSetMapper;
use Windwalker\ORM\ORM;
use Windwalker\Utilities\Utf8String;

/**
 * Category Seeder
 *
 * @var Seeder          $seeder
 * @var ORM             $orm
 * @var DatabaseAdapter $db
 */
$seeder->import(
    static function () use ($seeder, $orm, $db) {
        $types = [
            'article' => [
                'max_level' => 3,
                'number' => 30
            ],
            'product' => [
                'max_level' => 2,
                'number' => 10
            ],
        ];

        $faker = $seeder->faker('en_US');

        /** @var NestedSetMapper<Category> $mapper */
        $mapper = $orm->mapper(Category::class);
        $userIds = $orm->findColumn(User::class, 'id')->dump();

        $existsRecordIds = [];

        foreach ($types as $type => $detail) {
            $maxLevel = $detail['max_level'];

            $existsRecordIds[$type] = [1];

            foreach (range(1, $detail['number']) as $i) {
                /** @var Category $item */
                $item = $mapper->createEntity();

                $item->setType($type);
                $item->setTitle(Utf8String::ucwords($faker->sentence(3)));
                $item->setAlias(SlugHelper::safe($item->getTitle()));
                $item->setDescription($faker->paragraph(5));
                $item->setImage($faker->unsplashImage(800, 600));
                $item->setState(BasicState::from($faker->randomElement([1, 1, 1, 0])));
                $item->setCreated($created = $faker->dateTimeThisYear());
                $item->setModified($created->modify('+10days'));
                $item->setCreatedBy((int) $faker->randomElement($userIds));

                $mapper->setPosition(
                    $item,
                    $faker->randomElement($existsRecordIds[$item->getType()]),
                    Position::LAST_CHILD
                );

                /** @var Category $item */
                $item = $mapper->createOne($item);

                if ($item->getLevel() < $maxLevel) {
                    $existsRecordIds[$item->getType()][] = $item->getId();
                }

                $seeder->outCounting();
            }
        }
    }
);

$seeder->clear(
    static function () use ($seeder, $orm, $db) {
        //
    }
);
