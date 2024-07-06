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
        Schema::create('one_time_password', function (Blueprint $table) {
            $table->uuid('otp_id')->primary();
            $table->uuid('document_id')->nullable();
            $table->uuid('user_id');
            $table->string('otp');
            $table->string('type');
            $table->timestamp('expired_at');
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users');
            $table->foreign('document_id')->references('document_id')->on('documents');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
