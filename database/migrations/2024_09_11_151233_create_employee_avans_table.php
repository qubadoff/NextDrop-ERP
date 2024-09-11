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
        Schema::create('employee_avans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_id');
            $table->date('date');
            $table->float('amount');
            $table->text('reason')->nullable();
            $table->integer('status')->default(\App\Employee\EmployeeAvansStatus::PENDING->value);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_avans');
    }
};
