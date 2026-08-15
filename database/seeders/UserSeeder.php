<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            ['username' => 'SPV', 'name' => 'SPV Bisnis Ritel dan Kemitraan'],
            ['username' => '55700', 'name' => '55700 - KC Bantul'],
            ['username' => '55700D1', 'name' => '55700D1 - LE Bantul'],
            ['username' => '55751', 'name' => '55751 - KCP Pajangan'],
            ['username' => '55761', 'name' => '55761 - KCP pandak'],
            ['username' => '55762', 'name' => '55762 - KCP Srandakan'],
            ['username' => '55763', 'name' => '55763 - KCP Sanden'],
            ['username' => '55764', 'name' => '55764 - KCP Bambanglipuro'],
            ['username' => '55771', 'name' => '55771 - KCP Pundong'],
            ['username' => '55772', 'name' => '55772 - KCP Kretek'],
            ['username' => '55781', 'name' => '55781 - KCP Jetis'],
            ['username' => '55782', 'name' => '55782 - KCP Imogiri'],
            ['username' => '55783', 'name' => '55783 - KCP Dlingo'],
            ['username' => '55791', 'name' => '55791 - KCP Pleret'],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

    }
}
