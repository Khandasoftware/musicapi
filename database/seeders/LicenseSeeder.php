<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\License;

class LicenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        License::create([
            "title"=>"Free",
            "description"=>"Free to use for commercial use, only attribution will be appriciated",
            "thumbnail"=>"free-license.png"
        ]);
        License::create([
            "title"=>"Lease",
            "description"=>"Will be used multiple times by other musicians, you will lease royalties for commercial use",
            "thumbnail"=>"lease-license.png"
        ]);
        License::create([
            "title"=>"Exclussive",
            "description"=>"Buy once, we automatically remove it from selling, you own all rights, you will get all stems",
            "thumbnail"=>"exclusive-license.png"
        ]);
    }
}
