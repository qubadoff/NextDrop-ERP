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
        Schema::create('employee_penals', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_id');
            $table->dateTime('date');
            $table->float('penal_amount');
            $table->text('reason')->nullable();
            $table->integer('penal_type')->default(\App\Employee\EmployeePenalTypeEnum::ONETIME->value);
            $table->integer('status')->default(\App\Employee\EmployeePenalStatus::PENDING->value);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_penals');
    }
};
