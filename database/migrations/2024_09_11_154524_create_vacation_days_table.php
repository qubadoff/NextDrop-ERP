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
        Schema::create('vacation_days', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_id');
            $table->integer('vacation_all_days_count')->nullable();
            $table->date('vacation_start_date');
            $table->date('vacation_end_date');
            $table->float('amount')->nullable();
            $table->integer('vacation_pay_type')->default(\App\Vacation\VacationPayTypeEnum::PAID->value);
            $table->integer('vacation_type')->default(\App\Vacation\VacationTypeEnum::OFFICIAL->value);
            $table->integer('status')->default(\App\Vacation\VacationStatusEnum::PENDING->value);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacation_days');
    }
};
