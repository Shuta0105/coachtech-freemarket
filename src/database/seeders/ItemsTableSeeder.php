<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => '腕時計',
            'price' => 15000,
            'brand' => 'Rolax',
            'detail' => 'スタイリッシュなデザインのメンズ腕時計',
            'condition_id' => 1,
            'img' => 'Armani+Mens+Clock.jpg'
        ];
        Item::create($param);
        $param = [
            'name' => 'HDD',
            'price' => 5000,
            'brand' => '西芝',
            'detail' => '高速で信頼性の高いハードディスク',
            'condition_id' => 2,
            'img' => 'HDD+Hard+Disk.jpg'
        ];
        Item::create($param);
        $param = [
            'name' => '玉ねぎ3束',
            'price' => 300,
            'brand' => 'なし',
            'detail' => '新鮮な玉ねぎ3束のセット',
            'condition_id' => 3,
            'img' => 'iLoveIMG+d.jpg'
        ];
        Item::create($param);
        $param = [
            'name' => '革靴',
            'price' => 4000,
            'brand' => '',
            'detail' => 'クラシックなデザインの革靴',
            'condition_id' => 4,
            'img' => 'Leather+Shoes+Product+Photo.jpg'
        ];
        Item::create($param);
        $param = [
            'name' => 'ノートPC',
            'price' => 45000,
            'brand' => '',
            'detail' => '高性能なノートパソコン',
            'condition_id' => 1,
            'img' => 'Living+Room+Laptop.jpg'
        ];
        Item::create($param);
        $param = [
            'name' => 'マイク',
            'price' => 8000,
            'brand' => 'なし',
            'detail' => '高音質のレコーディング用マイク',
            'condition_id' => 2,
            'img' => 'Music+Mic+4632231.jpg'
        ];
        Item::create($param);
        $param = [
            'name' => 'ショルダーバッグ',
            'price' => 3500,
            'brand' => '',
            'detail' => 'おしゃれなショルダーバッグ',
            'condition_id' => 3,
            'img' => 'Purse+fashion+pocket.jpg'
        ];
        Item::create($param);
        $param = [
            'name' => 'タンブラー',
            'price' => 500,
            'brand' => 'なし',
            'detail' => '使いやすいタンブラー',
            'condition_id' => 4,
            'img' => 'Tumbler+souvenir.jpg'
        ];
        Item::create($param);
        $param = [
            'name' => 'コーヒーミル',
            'price' => 4000,
            'brand' => 'Starbacks',
            'detail' => '手動のコーヒーミル',
            'condition_id' => 1,
            'img' => 'Waitress+with+Coffee+Grinder.jpg'
        ];
        Item::create($param);
        $param = [
            'name' => 'メイクセット',
            'price' => 2500,
            'brand' => '',
            'detail' => '便利なメイクアップセット',
            'condition_id' => 2,
            'img' => '外出メイクアップセット.jpg'
        ];
        Item::create($param);
    }
}
