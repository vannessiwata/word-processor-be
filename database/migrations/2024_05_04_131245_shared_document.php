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
        Schema::create('document_shared', function (Blueprint $table) {
            $table->uuid('document_shared_id')->primary();
            $table->uuid('document_id');
            $table->string('user_id');
            $table->timestamps();

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
