<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {

            $table->id();

            $table->string('employee_code')->unique();

            $table->string('name');

            $table->string('email')->unique();

            $table->string('phone');

            $table->string('department');

            $table->date('joining_date');

            $table->integer('paid_leave_balance')->default(12);

            $table->integer('sick_leave_balance')->default(6);

            $table->integer('casual_leave_balance')->default(6);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};