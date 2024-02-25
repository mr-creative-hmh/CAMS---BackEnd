<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Clinic\Category;
use App\Models\Common\Role;
use App\Models\Common\User;
use App\Models\User\Specialization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // Roles Seed

        Role::factory()->create([
            'name' => 'Admin',
        ]);
        Role::factory()->create([
            'name' => 'Clinic Manager',
        ]);
        Role::factory()->create([
            'name' => 'Doctor',
        ]);
        Role::factory()->create([
            'name' => 'Patient',
        ]);

        // Create Admin User

        $created_user = User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@email.com',
            'email_verified_at' => now(),
            "password" => Hash::make("Password1"),
            "phone" => '011-3000003',
            "mobile" => '099999999',
            "address" => 'Administrator Address',
            "date_of_birth" => '1994-01-01',
            "gender" => 'Male',
            'remember_token' => Str::random(10)
        ]);

        $created_user->roles()->attach(User::ROLE_ADMIN);

        //Categories Seed

        Category::factory()->create([
            'name' => 'General',
        ]);
        Category::factory()->create([
            'name' => 'Ear Noose Throat',
        ]);
        Category::factory()->create([
            'name' => 'Ophthalmology',
        ]);
        Category::factory()->create([
            'name' => 'Gastrology',
        ]);
        Category::factory()->create([
            'name' => 'Respiration',
        ]);
        Category::factory()->create([
            'name' => 'Endocrine',
        ]);
        Category::factory()->create([
            'name' => 'Neurology',
        ]);
        Category::factory()->create([
            'name' => 'Genecology',
        ]);
        Category::factory()->create([
            'name' => 'Dermatology',
        ]);
        Category::factory()->create([
            'name' => 'Nephrology',
        ]);
        Category::factory()->create([
            'name' => 'Cardiology',
        ]);
        Category::factory()->create([
            'name' => 'Physical Treatment',
        ]);
        Category::factory()->create([
            'name' => 'Oncology',
        ]);
        Category::factory()->create([
            'name' => 'Psychiatry',
        ]);
        Category::factory()->create([
            'name' => 'Urology',
        ]);
        Category::factory()->create([
            'name' => 'Dental',
        ]);

        //Specializations Seed

        Specialization::factory()->create([
            'name' => 'Dental',
        ]);
        Specialization::factory()->create([
            'name' => 'Allergy and Immunology',
        ]);
        Specialization::factory()->create([
            'name' => 'Anesthesiology',
        ]);
        Specialization::factory()->create([
            'name' => 'Cardiology',
        ]);
        Specialization::factory()->create([
            'name' => 'Dermatology',
        ]);
        Specialization::factory()->create([
            'name' => 'Endocrinology',
        ]);
        Specialization::factory()->create([
            'name' => 'Family Medicine',
        ]);
        Specialization::factory()->create([
            'name' => 'Gastroenterology',
        ]);
        Specialization::factory()->create([
            'name' => 'Hematology',
        ]);
        Specialization::factory()->create([
            'name' => 'Infectious Disease',
        ]);
        Specialization::factory()->create([
            'name' => 'Nephrology',
        ]);
        Specialization::factory()->create([
            'name' => 'Neurology',
        ]);
        Specialization::factory()->create([
            'name' => 'Obstetrics and Gynecology',
        ]);
        Specialization::factory()->create([
            'name' => 'Ophthalmology',
        ]);
        Specialization::factory()->create([
            'name' => 'Otolaryngology',
        ]);
        Specialization::factory()->create([
            'name' => 'Psychiatry',
        ]);
        Specialization::factory()->create([
            'name' => 'Urology',
        ]);
    }
}
