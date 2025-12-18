<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    public function run()
    {
        $locations = [
            ['state' => 'Maharashtra', 'city' => 'Mumbai', 'area' => 'Andheri West', 'pincode' => '400053', 'shipping_charge' => 49],
            ['state' => 'Maharashtra', 'city' => 'Pune', 'area' => 'Hinjewadi', 'pincode' => '411057', 'shipping_charge' => 39],
            ['state' => 'Gujarat', 'city' => 'Ahmedabad', 'area' => 'Navrangpura', 'pincode' => '380009', 'shipping_charge' => 30],
            ['state' => 'Delhi', 'city' => 'New Delhi', 'area' => 'Connaught Place', 'pincode' => '110001', 'shipping_charge' => 60],
            ['state' => 'Karnataka', 'city' => 'Bengaluru', 'area' => 'Whitefield', 'pincode' => '560066', 'shipping_charge' => 45],
            ['state' => 'Tamil Nadu', 'city' => 'Chennai', 'area' => 'T Nagar', 'pincode' => '600017', 'shipping_charge' => 35],
            ['state' => 'Rajasthan', 'city' => 'Jaipur', 'area' => 'Malviya Nagar', 'pincode' => '302017', 'shipping_charge' => 40],
            ['state' => 'West Bengal', 'city' => 'Kolkata', 'area' => 'Salt Lake Sector 5', 'pincode' => '700091', 'shipping_charge' => 50],
            ['state' => 'Telangana', 'city' => 'Hyderabad', 'area' => 'Banjara Hills', 'pincode' => '500034', 'shipping_charge' => 55],
            ['state' => 'Uttar Pradesh', 'city' => 'Lucknow', 'area' => 'Gomti Nagar', 'pincode' => '226010', 'shipping_charge' => 25],
        ];

        foreach ($locations as $loc) {
            Location::create($loc);
        }
    }
}
