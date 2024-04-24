<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('sender_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('receiver_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('paytype_id')->constrained('paytypes')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('gateway_id')->nullable()->constrained('gateways')->restrictOnDelete()->cascadeOnUpdate();
            $table->string('description');
            $table->unsignedInteger('amount');
            $table->char('iban', 26);
            $table->string('attachment')->nullable();
            $table->string('authority')->nullable();
            $table->string('reference')->nullable();
            $table->boolean('is_confirmed')->default(0);
            $table->boolean('is_paid')->default(0);
            $table->timestamp('paid_at')->nullable();
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
