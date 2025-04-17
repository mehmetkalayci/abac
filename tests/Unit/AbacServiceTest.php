<?php

namespace Tests\Unit;

use App\Models\Attribute;
use App\Models\Policy;
use App\Models\PolicyAttribute;
use App\Models\User;
use App\Models\UserAttribute;
use App\Services\AbacService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class AbacServiceTest extends TestCase
{
    use RefreshDatabase;

    private AbacService $abacService;
    private User $user;
    private Attribute $departmentAttribute;
    private Attribute $roleAttribute;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->abacService = new AbacService();
        
        // Test kullanıcısı oluştur
        $this->user = User::factory()->create();
        
        // Öznitelikler oluştur
        $this->departmentAttribute = Attribute::create([
            'name' => 'department',
            'description' => 'Kullanıcının departmanı',
            'type' => 'string'
        ]);
        
        $this->roleAttribute = Attribute::create([
            'name' => 'role',
            'description' => 'Kullanıcının rolü',
            'type' => 'string'
        ]);
    }

    public function test_check_access_with_no_policies_returns_false(): void
    {
        $result = $this->abacService->checkAccess($this->user, 'documents', 'edit');
        
        $this->assertFalse($result);
    }

    public function test_check_access_with_matching_policy_returns_true(): void
    {
        // Kullanıcı özniteliklerini ayarla
        UserAttribute::create([
            'user_id' => $this->user->id,
            'attribute_id' => $this->departmentAttribute->id,
            'value' => 'IT'
        ]);
        
        UserAttribute::create([
            'user_id' => $this->user->id,
            'attribute_id' => $this->roleAttribute->id,
            'value' => 'manager'
        ]);

        // Politika oluştur
        $policy = Policy::create([
            'name' => 'IT Managers can edit documents',
            'description' => 'IT departmanındaki yöneticiler belgeleri düzenleyebilir',
            'effect' => 'allow',
            'resource' => 'documents',
            'action' => 'edit'
        ]);

        // Politika özniteliklerini ayarla
        PolicyAttribute::create([
            'policy_id' => $policy->id,
            'attribute_id' => $this->departmentAttribute->id,
            'operator' => 'equals',
            'value' => 'IT'
        ]);

        PolicyAttribute::create([
            'policy_id' => $policy->id,
            'attribute_id' => $this->roleAttribute->id,
            'operator' => 'equals',
            'value' => 'manager'
        ]);

        $result = $this->abacService->checkAccess($this->user, 'documents', 'edit');
        
        $this->assertTrue($result);
    }

    public function test_check_access_with_non_matching_policy_returns_false(): void
    {
        // Kullanıcı özniteliklerini ayarla
        UserAttribute::create([
            'user_id' => $this->user->id,
            'attribute_id' => $this->departmentAttribute->id,
            'value' => 'HR'
        ]);
        
        UserAttribute::create([
            'user_id' => $this->user->id,
            'attribute_id' => $this->roleAttribute->id,
            'value' => 'manager'
        ]);

        // Politika oluştur
        $policy = Policy::create([
            'name' => 'IT Managers can edit documents',
            'description' => 'IT departmanındaki yöneticiler belgeleri düzenleyebilir',
            'effect' => 'allow',
            'resource' => 'documents',
            'action' => 'edit'
        ]);

        // Politika özniteliklerini ayarla
        PolicyAttribute::create([
            'policy_id' => $policy->id,
            'attribute_id' => $this->departmentAttribute->id,
            'operator' => 'equals',
            'value' => 'IT'
        ]);

        PolicyAttribute::create([
            'policy_id' => $policy->id,
            'attribute_id' => $this->roleAttribute->id,
            'operator' => 'equals',
            'value' => 'manager'
        ]);

        $result = $this->abacService->checkAccess($this->user, 'documents', 'edit');
        
        $this->assertFalse($result);
    }

    public function test_check_access_uses_cache(): void
    {
        Cache::shouldReceive('remember')
            ->once()
            ->andReturn(true);

        $result = $this->abacService->checkAccess($this->user, 'documents', 'edit');
        
        $this->assertTrue($result);
    }

    public function test_check_access_with_multiple_policies_returns_correct_result(): void
    {
        // Kullanıcı özniteliklerini ayarla
        UserAttribute::create([
            'user_id' => $this->user->id,
            'attribute_id' => $this->departmentAttribute->id,
            'value' => 'IT'
        ]);
        
        UserAttribute::create([
            'user_id' => $this->user->id,
            'attribute_id' => $this->roleAttribute->id,
            'value' => 'manager'
        ]);

        // İzin veren politika
        $allowPolicy = Policy::create([
            'name' => 'IT Managers can edit documents',
            'description' => 'IT departmanındaki yöneticiler belgeleri düzenleyebilir',
            'effect' => 'allow',
            'resource' => 'documents',
            'action' => 'edit'
        ]);

        PolicyAttribute::create([
            'policy_id' => $allowPolicy->id,
            'attribute_id' => $this->departmentAttribute->id,
            'operator' => 'equals',
            'value' => 'IT'
        ]);

        // Reddeden politika
        $denyPolicy = Policy::create([
            'name' => 'Managers cannot edit documents',
            'description' => 'Yöneticiler belgeleri düzenleyemez',
            'effect' => 'deny',
            'resource' => 'documents',
            'action' => 'edit'
        ]);

        PolicyAttribute::create([
            'policy_id' => $denyPolicy->id,
            'attribute_id' => $this->roleAttribute->id,
            'operator' => 'equals',
            'value' => 'manager'
        ]);

        $result = $this->abacService->checkAccess($this->user, 'documents', 'edit');
        
        $this->assertFalse($result);
    }
} 