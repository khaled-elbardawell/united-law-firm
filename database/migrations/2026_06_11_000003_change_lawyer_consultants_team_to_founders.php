<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE lawyers MODIFY team_category ENUM('founders', 'consultants', 'administration', 'lawyers', 'trainees') NULL");

        DB::table('lawyers')
            ->where('team_category', 'consultants')
            ->update(['team_category' => 'founders']);

        DB::statement("ALTER TABLE lawyers MODIFY team_category ENUM('founders', 'administration', 'lawyers', 'trainees') NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE lawyers MODIFY team_category ENUM('founders', 'consultants', 'administration', 'lawyers', 'trainees') NULL");

        DB::table('lawyers')
            ->where('team_category', 'founders')
            ->update(['team_category' => 'consultants']);

        DB::statement("ALTER TABLE lawyers MODIFY team_category ENUM('consultants', 'administration', 'lawyers', 'trainees') NULL");
    }
};
