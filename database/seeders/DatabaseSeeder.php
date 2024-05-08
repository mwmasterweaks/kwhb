<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Models\User;
use App\Models\Employee;
use App\Models\Location;
use App\Models\Division;
use App\Models\Employment;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        Role::create(['id' => 1, 'name' => 'User']);
        Role::create(['id' => 2, 'name' => 'Line Manager']);
        Role::create(['id' => 3, 'name' => 'General Manager']);
        Role::create(['id' => 4, 'name' => 'HR']);
        Role::create(['id' => 5, 'name' => 'Finance']);
        Role::create(['id' => 6, 'name' => 'CEO']);
        Role::create(['id' => 7, 'name' => 'Board']);
        Role::create(['id' => 8, 'name' => 'Applicant']);
        Role::create(['id' => 9, 'name' => 'Administrator ']);

        Division::create(['id' => 1, 'name' => 'CEO']);
        Division::create(['id' => 2, 'name' => 'Corporate']);
        Division::create(['id' => 3, 'name' => 'Clinical']);
        Division::create(['id' => 4, 'name' => 'Program']);
        Division::create(['id' => 5, 'name' => 'Community']);
        Division::create(['id' => 6, 'name' => 'Board']);

        Location::create(['id' => 1, 'name' => 'Darwin']);
        Location::create(['id' => 2, 'name' => 'Katherine']);
        Location::create(['id' => 3, 'name' => 'Kalkarinji']);
        Location::create(['id' => 4, 'name' => 'Lajamanu']);
        Location::create(['id' => 5, 'name' => 'Timber Creek']);
        Location::create(['id' => 6, 'name' => 'Yarralin']);

        Employment::create(['id' => 1, 'name' => 'Volunteer']);
        Employment::create(['id' => 2, 'name' => 'Casual']);
        Employment::create(['id' => 3, 'name' => 'Full Time ']);
        Employment::create(['id' => 4, 'name' => 'PART-TIME 0.2']);
        Employment::create(['id' => 5, 'name' => 'PART-TIME 0.4']);
        Employment::create(['id' => 6, 'name' => 'PART-TIME 0.6']);
        Employment::create(['id' => 7, 'name' => 'PART-TIME 0.8']);
        Employment::create(['id' => 8, 'name' => 'Board']);

        Employee::create([
            'id' => 1,
            'first_name' => 'my firstname',
            'last_name' => 'my lastname',
            'gender' => 'male',
            'job_title' => 'developer',
            'status' => 'active',
            'location_id' => 1,
            'division_id' => 1,
            'employment_id' => 1,
        ]);

        User::create([
            'id' => 1,
            'name' => 'Admin',
            'username' => 'admin',
            'employee_id' => 1,
            'password' => bcrypt('admin123'),
        ]);
        $this->call([
            UsersTableSeeder::class,
            LeaveTypeSeeder::class,
        ]);
    }
}
