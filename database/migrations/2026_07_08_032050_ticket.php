<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('konser_id')
                ->constrained('konsers')
                ->cascadeOnDelete();
            $table->string('ticket_name');
            $table->decimal('price',12,2);
            $table->integer('stock');
            $table->enum('status',[
                'available',
                'sold_out'
            ])->default('available');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};