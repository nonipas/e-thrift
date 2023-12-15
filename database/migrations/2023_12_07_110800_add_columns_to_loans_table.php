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
        Schema::table('loans', function (Blueprint $table) {
            //add previous payment column, repayment start year column and parent loan id column
            $table->decimal('previous_payment', 15, 2)->default(0);
            $table->integer('repayment_start_year')->nullable();
            $table->integer('parent_loan_id')->default(0);
            $table->tinyInteger('paid_out')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            //
        });
    }
};
