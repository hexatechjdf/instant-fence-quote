<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceFitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_fits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ft_available_id')->constrained()->onDelete('cascade');
            $table->foreignId('fence_id')->constrained()->onDelete('cascade');
            $table->enum('type',['single','double']);
            $table->double('ft_price')->nullable();
            $table->double('ft_range')->nullable();
            $table->boolean('is_active')->default(0);
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
        Schema::dropIfExists('price_fits');
    }
}
