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
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_id')->default(0)->nullable();
            $table->integer('name_id')->default(\App\Employee\LanguageStatusEnum::AZ->value)->nullable();
            $table->integer('level_id')->default(\App\Employee\LanguageLevelEnum::A1->value)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
