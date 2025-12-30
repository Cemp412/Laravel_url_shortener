<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Helpers\password_helper;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Schema;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /* Schema::disableForeignKeyConstraints();
        User::truncate();
        Schema::enableForeignKeyConstraints(); */

        $password = config('constants.auth.pass_key');
        $user = User::firstOrCreate(
            [ 'email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => hashWithPepper($password), //bcrypt($password), 
                'company_id' => null
            
            ]);

        if(isset($user)) {
            $user->assignRole('superadmin');
        }
    }
}
