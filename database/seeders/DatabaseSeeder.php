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


        Role::create(['id' => 1, 'name' => 'USER']);
        Role::create(['id' => 2, 'name' => 'LINE MANAGER']);
        Role::create(['id' => 3, 'name' => 'GENERAL MANAGER']);
        Role::create(['id' => 4, 'name' => 'HR']);
        Role::create(['id' => 5, 'name' => 'FINANCE']);
        Role::create(['id' => 6, 'name' => 'CEO']);
        Role::create(['id' => 7, 'name' => 'BOARD']);
        Role::create(['id' => 8, 'name' => 'APPLICANT']);

        Division::create(['id' => 1, 'name' => 'CEO']);
        Division::create(['id' => 2, 'name' => 'CORP']);
        Division::create(['id' => 3, 'name' => 'CLINICAL']);
        Division::create(['id' => 4, 'name' => 'PROGRAM']);
        Division::create(['id' => 5, 'name' => 'COMMUNITY']);
        Division::create(['id' => 6, 'name' => 'BOARD']);

        Location::create(['id' => 1, 'name' => 'KATHERINE']);
        Location::create(['id' => 2, 'name' => 'DARWIN']);
        Location::create(['id' => 3, 'name' => 'SYDNEY']);
        Location::create(['id' => 4, 'name' => 'IRELAND']);
        Location::create(['id' => 5, 'name' => 'DAVAO']);
        Location::create(['id' => 6, 'name' => 'MANILA']);

        Employment::create(['id' => 1, 'name' => 'VOLUNTEER']);
        Employment::create(['id' => 2, 'name' => 'CASUAL']);
        Employment::create(['id' => 3, 'name' => 'FULL TIME']);
        Employment::create(['id' => 4, 'name' => 'PART-TIME 0.2']);
        Employment::create(['id' => 5, 'name' => 'PART-TIME 0.4']);
        Employment::create(['id' => 6, 'name' => 'PART-TIME 0.6']);

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
    }
}
