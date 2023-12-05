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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->integer('member_id');
            $table->string('beneficiary_type')->default('member');
            $table->string('beneficiary_name')->nullable();
            $table->string('beneficiary_account_no')->nullable();
            $table->string('beneficiary_bank')->nullable();
            $table->double('amount', 8, 2)->default(0);
            $table->double('interest', 8, 2)->default(0);
            $table->string('duration')->default(12);
            $table->double('monthly_repayment', 8, 2)->default(0);
            $table->double('total_repayment', 8, 2)->default(0);
            $table->double('balance', 8, 2)->default(0);
            $table->string('repayment_start_month');
            $table->string('repayment_status')->default('active');
            $table->string('type')->default('new');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
