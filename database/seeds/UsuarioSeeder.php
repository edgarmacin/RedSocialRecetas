<?php


use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Edgar',
            'email' => 'edgarmacin_1107@hotmail.com',
            'password' => Hash::make('12345678'),
            'url' => 'http://edgarmacin.com',
        ]);

        $user->perfil()->create();

        $user2 = User::create([
            'name' => 'Armando',
            'email' => 'armando@hotmail.com',
            'password' => Hash::make('12345678'),
            'url' => 'http://armando.com',
        ]);

        $user2->perfil()->create();

    }
}
