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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->integer('member_id')->nullable();
            $table->integer('payment_batch_id');
            $table->string('payment_type');
            $table->string('bank')->nullable();
            $table->string('beneficiary_name')->nullable();
            $table->string('beneficiary_account_no')->nullable();
            $table->double('amount', 8, 2)->default(0);
            $table->string('description')->nullable();
            $table->boolean('is_approved')->default(0);
            $table->string('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->boolean('is_processed')->default(0);
            $table->string('processed_by')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
