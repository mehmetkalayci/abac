<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attributes = [
            [
                'name' => 'department',
                'value' => 'Cardiology',
            ],
            [
                'name' => 'department',
                'value' => 'Emergency',
            ],
            [
                'name' => 'department',
                'value' => 'Internal Medicine',
            ],
            [
                'name' => 'role',
                'value' => 'Doctor',
            ],
            [
                'name' => 'role',
                'value' => 'Nurse',
            ],
            [
                'name' => 'role',
                'value' => 'Specialist',
            ],
            [
                'name' => 'security_clearance',
                'value' => '3',
            ],
            [
                'name' => 'security_clearance',
                'value' => '4',
            ],
            [
                'name' => 'security_clearance',
                'value' => '5',
            ],
            [
                'name' => 'shift_hours',
                'value' => '08:00 to 17:00',
            ],
            [
                'name' => 'shift_hours',
                'value' => '12:00 to 20:00',
            ],
            [
                'name' => 'shift_hours',
                'value' => '09:00 to 18:00',
            ],
            [
                'name' => 'working_days',
                'value' => 'Monday to Friday',
            ],
            [
                'name' => 'working_days',
                'value' => 'Monday to Saturday',
            ],
            [
                'name' => 'required_training',
                'value' => 'HIPAA, Data Privacy, Medical Ethics, Emergency Procedures',
            ],
            [
                'name' => 'required_training',
                'value' => 'HIPAA, Data Privacy, Emergency Procedures',
            ],
            [
                'name' => 'required_training',
                'value' => 'HIPAA, Data Privacy, Medical Ethics, Specialized Procedures',
            ],
        ];

        foreach ($attributes as $attribute) {
            Attribute::create($attribute);
        }
    }
}
