<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('final_policy_pdfs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('policy_id')
                ->constrained('purchase_policy')
                ->cascadeOnDelete();

            $table->text('final_pdf_url');

            $table->foreignId('client_id')
                ->constrained('clients')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('final_policy_pdfs');
    }
};