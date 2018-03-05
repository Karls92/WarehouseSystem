<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(UserSeeder::class);
        $this->call(PanelConfigSeeder::class);
        $this->call(RecentActivitySeeder::class);
        $this->call(SiteConfigSeeder::class);
        $this->call(VisitSeeder::class);
        $this->call(UnitOfMeasureSeeder::class);
        $this->call(ClassificationSeeder::class);
        $this->call(BrandSeeder::class);
        $this->call(ModelSeeder::class);
        $this->call(StateSeeder::class);
        $this->call(CitySeeder::class);
        //$this->call(ClientSeeder::class);
        //$this->call(ProductSeeder::class);
        //$this->call(EntryOrderSeeder::class);
        //$this->call(EntryOrderProductSeeder::class);
        //$this->call(OutOrderSeeder::class);
        //$this->call(OutOrderProductSeeder::class);
        //$this->call(DevolutionOrderSeeder::class);
        //$this->call(DevolutionOrderProductSeeder::class);
    
        Model::reguard();
    }
}
