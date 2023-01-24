<?php

namespace Database\Seeders;

use App\Models\Configuration;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConfigurationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Configuration::create([
            'group'      => 'category',
            'name'       => 'PK',
            'value'      => '1',
            'sort_order' => '1',
        ]);
        Configuration::create([
            'group'      => 'category',
            'name'       => 'MA',
            'value'      => '0',
            'sort_order' => '2',
        ]);
        Configuration::create([
            'group'      => 'type',
            'name'       => 'Akademik',
            'value'      => '1',
            'sort_order' => '1',
        ]);
        Configuration::create([
            'group'      => 'type',
            'name'       => 'Umum',
            'value'      => '0',
            'sort_order' => '2',
        ]);
        Configuration::create([
            'group'      => 'proposal status',
            'name'       => 'Diajukan',
            'value'      => '1',
            'sort_order' => '1',
        ]);
        Configuration::create([
            'group'      => 'proposal status',
            'name'       => 'Terverifikasi keuangan',
            'value'      => '2',
            'sort_order' => '2',
        ]);
        Configuration::create([
            'group'      => 'proposal status',
            'name'       => 'Ditolak keuangan',
            'value'      => '3',
            'sort_order' => '3',
        ]);
        Configuration::create([
            'group'      => 'proposal status',
            'name'       => 'Terverifikasi pimpinan',
            'value'      => '4',
            'sort_order' => '4',
        ]);
        Configuration::create([
            'group'      => 'proposal status',
            'name'       => 'Ditolak pimpinan',
            'value'      => '5',
            'sort_order' => '5',
        ]);
        Configuration::create([
            'group'      => 'proposal status',
            'name'       => 'Dicairkan',
            'value'      => '6',
            'sort_order' => '6',
        ]);
        Configuration::create([
            'group'      => 'proposal status',
            'name'       => 'Selesai',
            'value'      => '7',
            'sort_order' => '7',
        ]);
        Configuration::create([
            'group'      => 'report status',
            'name'       => 'Diajukan',
            'value'      => '1',
            'sort_order' => '1',
        ]);
        Configuration::create([
            'group'      => 'report status',
            'name'       => 'Terverifikasi keuangan',
            'value'      => '2',
            'sort_order' => '2',
        ]);
        Configuration::create([
            'group'      => 'report status',
            'name'       => 'Ditolak keuangan',
            'value'      => '3',
            'sort_order' => '3',
        ]);
        Configuration::create([
            'group'      => 'report status',
            'name'       => 'Terverifikasi rektor',
            'value'      => '4',
            'sort_order' => '4',
        ]);
        Configuration::create([
            'group'      => 'report status',
            'name'       => 'Ditolak rektor',
            'value'      => '5',
            'sort_order' => '5',
        ]);
        Configuration::create([
            'group'      => 'report status',
            'name'       => 'Terverifikasi pimpinan',
            'value'      => '6',
            'sort_order' => '6',
        ]);
        Configuration::create([
            'group'      => 'report status',
            'name'       => 'Ditolak pimpinan',
            'value'      => '7',
            'sort_order' => '7',
        ]);
        Configuration::create([
            'group'      => 'report status',
            'name'       => 'Selesai',
            'value'      => '8',
            'sort_order' => '8',
        ]);
        Configuration::create([
            'group'      => 'approver',
            'name'       => '2500000',
            'value'      => json_encode(['', '']),
            'sort_order' => '1',
        ]);
        Configuration::create([
            'group'      => 'approver',
            'name'       => '5000000',
            'value'      => json_encode(['', '']),
            'sort_order' => '2',
        ]);
        Configuration::create([
            'group'      => 'approver',
            'name'       => '10000000',
            'value'      => json_encode(['', '']),
            'sort_order' => '3',
        ]);
        Configuration::create([
            'group'      => 'approver',
            'name'       => '0',
            'value'      => json_encode(['', '']),
            'sort_order' => '4',
        ]);
        Configuration::create([
            'group'      => 'bank',
            'name'       => 'Mandiri',
            'value'      => '123456789',
            'sort_order' => '1',
        ]);
        Configuration::create([
            'group'      => 'bank',
            'name'       => 'BTN',
            'value'      => '123456789',
            'sort_order' => '2',
        ]);
    }
}
