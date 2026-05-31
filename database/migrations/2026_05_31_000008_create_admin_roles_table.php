<?php

use App\Support\AdminPermissions;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->json('permissions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_system')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        $now = now();
        foreach ([
            'admin' => 'مدير كامل الصلاحيات',
            'manager' => 'مسؤول',
            'editor' => 'محرر',
        ] as $slug => $name) {
            DB::table('admin_roles')->insert([
                'name' => $name,
                'slug' => $slug,
                'permissions' => json_encode(AdminPermissions::presetForRole($slug), JSON_UNESCAPED_UNICODE),
                'is_active' => true,
                'is_system' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('admin_role_id')->nullable()->after('role')->constrained('admin_roles')->nullOnDelete();
        });

        $roles = DB::table('admin_roles')->pluck('id', 'slug');
        DB::table('users')->orderBy('id')->chunkById(100, function ($users) use ($roles) {
            foreach ($users as $user) {
                $slug = Str::lower((string) ($user->role ?: 'editor'));
                DB::table('users')->where('id', $user->id)->update([
                    'admin_role_id' => $roles[$slug] ?? $roles['editor'] ?? null,
                ]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('admin_role_id');
        });

        Schema::dropIfExists('admin_roles');
    }
};
