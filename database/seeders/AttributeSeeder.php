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
                'description' => 'Departman bilgisi - Kardiyoloji',
                'type' => 'string'
            ],
            [
                'name' => 'department',
                'description' => 'Departman bilgisi - Acil',
                'type' => 'string'
            ],
            [
                'name' => 'department',
                'description' => 'Departman bilgisi - İç Hastalıkları',
                'type' => 'string'
            ],
            [
                'name' => 'role',
                'description' => 'Kullanıcı rolü - Doktor',
                'type' => 'string'
            ],
            [
                'name' => 'role',
                'description' => 'Kullanıcı rolü - Hemşire',
                'type' => 'string'
            ],
            [
                'name' => 'role',
                'description' => 'Kullanıcı rolü - Uzman',
                'type' => 'string'
            ],
            [
                'name' => 'security_clearance',
                'description' => 'Güvenlik seviyesi - 3',
                'type' => 'integer'
            ],
            [
                'name' => 'security_clearance',
                'description' => 'Güvenlik seviyesi - 4',
                'type' => 'integer'
            ],
            [
                'name' => 'security_clearance',
                'description' => 'Güvenlik seviyesi - 5',
                'type' => 'integer'
            ],
            [
                'name' => 'shift_hours',
                'description' => 'Çalışma saatleri - 08:00-17:00',
                'type' => 'string'
            ],
            [
                'name' => 'shift_hours',
                'description' => 'Çalışma saatleri - 12:00-20:00',
                'type' => 'string'
            ],
            [
                'name' => 'shift_hours',
                'description' => 'Çalışma saatleri - 09:00-18:00',
                'type' => 'string'
            ],
            [
                'name' => 'working_days',
                'description' => 'Çalışma günleri - Pazartesi-Cuma',
                'type' => 'string'
            ],
            [
                'name' => 'working_days',
                'description' => 'Çalışma günleri - Pazartesi-Cumartesi',
                'type' => 'string'
            ],
            [
                'name' => 'required_training',
                'description' => 'Gerekli eğitimler - HIPAA, Veri Gizliliği, Tıbbi Etik, Acil Durum Prosedürleri',
                'type' => 'array'
            ],
            [
                'name' => 'required_training',
                'description' => 'Gerekli eğitimler - HIPAA, Veri Gizliliği, Acil Durum Prosedürleri',
                'type' => 'array'
            ],
            [
                'name' => 'required_training',
                'description' => 'Gerekli eğitimler - HIPAA, Veri Gizliliği, Tıbbi Etik, Özel Prosedürler',
                'type' => 'array'
            ],
        ];

        foreach ($attributes as $attribute) {
            Attribute::create($attribute);
        }
    }
}
