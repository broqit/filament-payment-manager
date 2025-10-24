<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('driver');
            $table->string('currency');
            $table->boolean('is_active')->default(false);
            $table->boolean('is_default')->default(false);
            $table->json('config')->nullable();
            $table->string('callback_url')->nullable();
            $table->text('redirect_form_view')->nullable();
            $table->text('redirect_form_html')->nullable();
            $table->integer('sort_order')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_gateways');
    }
};