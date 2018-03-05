<?php

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
        $user = DB::table('users')->get();
        
        if(!$user)
        {
            DB::table('users')->insert([
               [
                   'id'         => 1,
                   'username'   => 'carmen',
                   'first_name'  => 'Carmen',
                   'last_name'   => 'Bravo',
                   'phone'      => '(0412) 087-0460',
                   'image'      => 'default.png',
                   'password'   => bcrypt('21348535'),
                   'email'      => 'nbravoalcala@gmail.com',
                   'type'       => 'admin',
                   'level'      => 0,
                   'created_at' => date('Y-m-d H:i:s', time()),
                   'updated_at' => date('Y-m-d H:i:s', time()),
               ],
               [
                   'id'         => 2,
                   'username'   => 'nitcelis',
                   'first_name'  => 'Nitcelis',
                   'last_name'   => 'Bravo',
                   'phone'      => '(0000) 000-0000',
                   'image'      => 'default.png',
                   'password'   => bcrypt('123456'),
                   'email'      => 'nitcelis@gmail.com',
                   'type'       => 'admin',
                   'level'      => 0,
                   'created_at' => date('Y-m-d H:i:s', time()),
                   'updated_at' => date('Y-m-d H:i:s', time()),
               ],
               [
                   'id'         => 3,
                   'username'   => 'admin',
                   'first_name'  => 'Admin',
                   'last_name'   => 'Admin',
                   'phone'      => '(0000) 000-0000',
                   'image'      => 'default.png',
                   'password'   => bcrypt('123456'),
                   'email'      => 'admin@gmail.com',
                   'type'       => 'admin',
                   'level'      => 0,
                   'created_at' => date('Y-m-d H:i:s', time()),
                   'updated_at' => date('Y-m-d H:i:s', time()),
               ],
               [
                   'id'         => 4,
                   'username'   => 'usuario1',
                   'first_name'  => 'Usuario',
                   'last_name'   => 'Usuario',
                   'phone'      => '(0000) 000-0000',
                   'image'      => 'default.png',
                   'password'   => bcrypt('123456'),
                   'email'      => 'usuario1@gmail.com',
                   'type'       => 'admin',
                   'level'      => 1,
                   'created_at' => date('Y-m-d H:i:s', time()),
                   'updated_at' => date('Y-m-d H:i:s', time()),
               ],
               [
                   'id'         => 5,
                   'username'   => 'usuario2',
                   'first_name'  => 'Usuario',
                   'last_name'   => 'Usuario',
                   'phone'      => '(0000) 000-0000',
                   'image'      => 'default.png',
                   'password'   => bcrypt('123456'),
                   'email'      => 'usuario2@gmail.com',
                   'type'       => 'admin',
                   'level'      => 1,
                   'created_at' => date('Y-m-d H:i:s', time()),
                   'updated_at' => date('Y-m-d H:i:s', time()),
               ],
               [
                   'id'         => 6,
                   'username'   => 'usuario3',
                   'first_name'  => 'Usuario',
                   'last_name'   => 'Usuario',
                   'phone'      => '(0000) 000-0000',
                   'image'      => 'default.png',
                   'password'   => bcrypt('123456'),
                   'email'      => 'usuario3@gmail.com',
                   'type'       => 'admin',
                   'level'      => 1,
                   'created_at' => date('Y-m-d H:i:s', time()),
                   'updated_at' => date('Y-m-d H:i:s', time()),
               ],
           ]);
        }
    }
}
