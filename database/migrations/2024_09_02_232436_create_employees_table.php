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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->integer('department_id');
            $table->integer('position_id');
            $table->string('name');
            $table->string('surname');
            $table->string('father_name');
            $table->date('birthday');
            $table->integer('sex')->default(\App\Employee\EmployeeSexEnum::MALE->value);
            $table->text('legal_address')->nullable();
            $table->text('current_address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('nationality')->nullable();
            $table->integer('marital_status')->default(\App\Employee\MaritalStatusEnum::SINGLE->value);
            $table->integer('military_status')->default(\App\Employee\MilitaryStatusEnum::PASSED->value);
            $table->string('id_number')->nullable();
            $table->string('id_pin_code')->nullable();
            $table->string('ssn')->nullable();
            $table->integer('driver_license')->default(1);
            $table->string('driver_license_number')->nullable();
            $table->integer('car')->default(\App\Employee\CarStatusEnum::NO->value);
            $table->text('photo')->nullable();
            $table->text('id_photo_front')->nullable();
            $table->text('id_photo_back')->nullable();

            $table->integer('work_experience')->default(0);
            $table->integer('state')->default(\App\Employee\EmployeeStateStatus::FULL->value);
            $table->date('start_work_date');
            $table->float('gross_salary')->default(0);
            $table->float('net_salary')->default(0);
            $table->integer('work_status')->default(\App\Employee\EmployeeWorkStatus::OFFICIAL->value);

            $table->text('other_info')->nullable();
            $table->text('docs')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
