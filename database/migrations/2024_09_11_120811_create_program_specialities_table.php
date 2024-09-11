<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('program_specialities', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_id')->default(0);
            $table->string('name')->nullable();
            $table->integer('level')->default(\App\Employee\ProgramSpecialityLevelEnum::MIDDLE->value);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_specialities');
    }
};
