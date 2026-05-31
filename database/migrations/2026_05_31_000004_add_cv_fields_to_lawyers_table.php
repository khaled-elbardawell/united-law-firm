<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lawyers', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
            $table->text('professional_summary')->nullable()->after('bio');
            $table->string('bar_number')->nullable()->after('professional_summary');
            $table->unsignedInteger('years_experience')->nullable()->after('bar_number');
            $table->json('education')->nullable()->after('years_experience');
            $table->json('experience')->nullable()->after('education');
            $table->json('certifications')->nullable()->after('experience');
            $table->json('languages')->nullable()->after('certifications');
            $table->json('memberships')->nullable()->after('languages');
            $table->json('awards')->nullable()->after('memberships');
            $table->json('court_admissions')->nullable()->after('awards');
            $table->string('linkedin_url')->nullable()->after('photo');
            $table->string('website_url')->nullable()->after('linkedin_url');
            $table->string('cv_file')->nullable()->after('website_url');
        });

        DB::table('lawyers')
            ->select('id', 'name')
            ->orderBy('id')
            ->get()
            ->each(function ($lawyer) {
                $base = Str::slug($lawyer->name) ?: 'lawyer-'.$lawyer->id;
                $slug = $base;
                $counter = 2;

                while (DB::table('lawyers')->where('slug', $slug)->where('id', '!=', $lawyer->id)->exists()) {
                    $slug = $base.'-'.$counter++;
                }

                DB::table('lawyers')->where('id', $lawyer->id)->update(['slug' => $slug]);
            });
    }

    public function down(): void
    {
        Schema::table('lawyers', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn([
                'slug',
                'professional_summary',
                'bar_number',
                'years_experience',
                'education',
                'experience',
                'certifications',
                'languages',
                'memberships',
                'awards',
                'court_admissions',
                'linkedin_url',
                'website_url',
                'cv_file',
            ]);
        });
    }
};
