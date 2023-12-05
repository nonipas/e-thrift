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
        Schema::create('payment_batches', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->double('total_amount', 8, 2)->default(0);
            $table->integer('size')->default(0);
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('payment_batches');
    }
};
