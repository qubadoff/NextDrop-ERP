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
        Schema::create('employee_awards', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_id');
            $table->dateTime('date');
            $table->float('award_amount');
            $table->text('reason')->nullable();
            $table->string('who_added')->nullable();
            $table->integer('award_type')->default(\App\Employee\EmployeeAwardType::ADD_SALARY->value);
            $table->integer('status')->default(\App\Employee\EmployeeAwardStatus::PENDING->value);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_awards');
    }
};
