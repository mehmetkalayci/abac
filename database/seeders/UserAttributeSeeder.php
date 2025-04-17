<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\User;
use App\Models\UserAttribute;
use Illuminate\Database\Seeder;

class UserAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attributes = Attribute::all();
        $users = User::all();

        foreach ($users as $user) {
            if ($user->email === 'doctor@example.com') {
                $this->createDoctorAttributes($user, $attributes);
            } elseif ($user->email === 'nurse@example.com') {
                $this->createNurseAttributes($user, $attributes);
            } elseif ($user->email === 'specialist@example.com') {
                $this->createSpecialistAttributes($user, $attributes);
            }
        }
    }

    private function createDoctorAttributes($user, $attributes)
    {
        $doctorAttributes = [
            'department' => 'Cardiology',
            'role' => 'Doctor',
            'security_clearance' => '4',
            'shift_hours' => '08:00 to 17:00',
            'working_days' => 'Monday to Friday',
            'required_training' => 'HIPAA, Data Privacy, Medical Ethics, Emergency Procedures',
        ];

        $this->createUserAttributes($user, $attributes, $doctorAttributes);
    }

    private function createNurseAttributes($user, $attributes)
    {
        $nurseAttributes = [
            'department' => 'Emergency',
            'role' => 'Nurse',
            'security_clearance' => '3',
            'shift_hours' => '12:00 to 20:00',
            'working_days' => 'Monday to Saturday',
            'required_training' => 'HIPAA, Data Privacy, Emergency Procedures',
        ];

        $this->createUserAttributes($user, $attributes, $nurseAttributes);
    }

    private function createSpecialistAttributes($user, $attributes)
    {
        $specialistAttributes = [
            'department' => 'Internal Medicine',
            'role' => 'Specialist',
            'security_clearance' => '5',
            'shift_hours' => '09:00 to 18:00',
            'working_days' => 'Monday to Friday',
            'required_training' => 'HIPAA, Data Privacy, Medical Ethics, Specialized Procedures',
        ];

        $this->createUserAttributes($user, $attributes, $specialistAttributes);
    }

    private function createUserAttributes($user, $attributes, $userAttributes)
    {
        foreach ($userAttributes as $name => $value) {
            $attribute = $attributes->where('name', $name)->first();
            if ($attribute) {
                UserAttribute::create([
                    'user_id' => $user->id,
                    'attribute_id' => $attribute->id,
                    'value' => $value,
                ]);
            }
        }
    }
}
