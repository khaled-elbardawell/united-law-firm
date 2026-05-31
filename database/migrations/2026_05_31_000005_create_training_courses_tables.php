<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('trainer_name')->nullable();
            $table->string('location')->nullable();
            $table->unsignedInteger('capacity')->nullable();
            $table->unsignedInteger('course_days')->default(1);
            $table->decimal('price', 10, 2)->nullable();
            $table->date('course_starts_at')->nullable();
            $table->date('course_ends_at')->nullable();
            $table->dateTime('registration_starts_at')->nullable();
            $table->dateTime('registration_ends_at')->nullable();
            $table->string('hero_image')->nullable();
            $table->text('summary');
            $table->longText('description')->nullable();
            $table->json('outcomes')->nullable();
            $table->json('requirements')->nullable();
            $table->text('schedule_notes')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('training_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_course_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('profession')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('registered');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('training_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_registration_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('day_number');
            $table->date('attendance_date')->nullable();
            $table->string('status')->default('absent');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['training_registration_id', 'day_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_attendances');
        Schema::dropIfExists('training_registrations');
        Schema::dropIfExists('training_courses');
    }
};
