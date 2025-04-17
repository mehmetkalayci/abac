<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\Policy;
use App\Models\PolicyAttribute;
use Illuminate\Database\Seeder;

class PolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tıbbi kayıt görüntüleme politikası
        $medicalRecordViewPolicy = Policy::create([
            'name' => 'Medical Record View Access',
            'description' => 'Tıbbi kayıtları görüntüleme erişim politikası',
            'effect' => 'allow',
            'resource' => 'medical_records',
            'action' => 'view',
            'conditions' => [
                'time_based' => [
                    'type' => 'time_window',
                    'start' => '08:00',
                    'end' => '18:00',
                    'days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
                ],
                'emergency_override' => true,
                'required_training' => ['HIPAA', 'Data Privacy'],
                'audit_level' => 'high'
            ]
        ]);

        // Politika özniteliklerini ekle
        $attributes = Attribute::all();
        
        PolicyAttribute::create([
            'policy_id' => $medicalRecordViewPolicy->id,
            'attribute_id' => $attributes->where('name', 'department')->first()->id,
            'operator' => 'in',
            'value' => json_encode(['Cardiology', 'Emergency', 'Internal Medicine'])
        ]);

        PolicyAttribute::create([
            'policy_id' => $medicalRecordViewPolicy->id,
            'attribute_id' => $attributes->where('name', 'role')->first()->id,
            'operator' => 'in',
            'value' => json_encode(['Doctor', 'Nurse', 'Specialist'])
        ]);

        PolicyAttribute::create([
            'policy_id' => $medicalRecordViewPolicy->id,
            'attribute_id' => $attributes->where('name', 'security_clearance')->first()->id,
            'operator' => 'greater_than_or_equal',
            'value' => '3'
        ]);

        // Tıbbi kayıt düzenleme politikası
        $medicalRecordEditPolicy = Policy::create([
            'name' => 'Medical Record Edit Access',
            'description' => 'Tıbbi kayıtları düzenleme erişim politikası',
            'effect' => 'allow',
            'resource' => 'medical_records',
            'action' => 'edit',
            'conditions' => [
                'time_based' => [
                    'type' => 'time_window',
                    'start' => '08:00',
                    'end' => '18:00',
                    'days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
                ],
                'required_training' => ['HIPAA', 'Data Privacy', 'Medical Ethics'],
                'audit_level' => 'critical'
            ]
        ]);

        PolicyAttribute::create([
            'policy_id' => $medicalRecordEditPolicy->id,
            'attribute_id' => $attributes->where('name', 'role')->first()->id,
            'operator' => 'equals',
            'value' => 'Doctor'
        ]);

        PolicyAttribute::create([
            'policy_id' => $medicalRecordEditPolicy->id,
            'attribute_id' => $attributes->where('name', 'patient_doctor')->first()->id,
            'operator' => 'equals',
            'value' => ':current_user_id'
        ]);

        // Hassas kayıt erişim politikası
        $sensitiveRecordPolicy = Policy::create([
            'name' => 'Sensitive Record Access',
            'description' => 'Hassas kayıtlara erişim politikası',
            'effect' => 'deny',
            'resource' => 'medical_records',
            'action' => 'view',
            'conditions' => [
                'time_based' => [
                    'type' => 'time_window',
                    'start' => '00:00',
                    'end' => '23:59',
                    'days' => ['Saturday', 'Sunday']
                ]
            ]
        ]);

        PolicyAttribute::create([
            'policy_id' => $sensitiveRecordPolicy->id,
            'attribute_id' => $attributes->where('name', 'record_sensitivity')->first()->id,
            'operator' => 'equals',
            'value' => 'high'
        ]);

        PolicyAttribute::create([
            'policy_id' => $sensitiveRecordPolicy->id,
            'attribute_id' => $attributes->where('name', 'security_clearance')->first()->id,
            'operator' => 'less_than',
            'value' => '4'
        ]);
    }
}
