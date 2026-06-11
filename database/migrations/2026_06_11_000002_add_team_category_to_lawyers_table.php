<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $categories = ['founders', 'administration', 'lawyers', 'trainees'];

        Schema::table('lawyers', function (Blueprint $table) use ($categories) {
            $table->enum('team_category', $categories)
                ->nullable()
                ->after('slug');
        });
    }

    public function down(): void
    {
        Schema::table('lawyers', function (Blueprint $table) {
            $table->dropColumn('team_category');
        });
    }
};
