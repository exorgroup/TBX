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
        Schema::create('taxes', function (Blueprint $table) {
            $table->id('TaxID');
            $table->string('TaxName', 20);
            $table->decimal('TaxRate', 10, 2);
            $table->string('SHASignature', 150)->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Add index to improve search performance
            $table->index('TaxName');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxes');
    }
};
