<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockReductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_reductions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_stock_id')->constrained('item_stocks')->onDelete('cascade');
            $table->foreignId('stock_id')->constrained('stocks')->onDelete('cascade');
            $table->integer('expense')->nullable();
            $table->text('description')->nullable();
            $table->date('date')->nullable();
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
        Schema::dropIfExists('stock_reductions');
    }
}
