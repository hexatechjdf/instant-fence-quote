<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFenceFtAvailablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fence_ft_availables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fence_id')->constrained()->onDelete('cascade');
            $table->foreignId('ft_available_id')->constrained()->onDelete('cascade');
            $table->decimal('price' ,10,2)->nullable();
            $table->decimal('range',10,2)->nullable();
            $table->boolean('is_available')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fence_ft_availables');
    }
}
