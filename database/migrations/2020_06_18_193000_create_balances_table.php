<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balances', function (Blueprint $table) {
            $table->id();
            $table->double('value_open', 10,2)->nullable();
            $table->datetime('date_open');
            $table->double('value_previous_close', 10,2)->nullable();
            $table->double('value_close', 10,2)->nullable();
            $table->datetime('date_close')->nullable();
            $table->double('value_cash', 10,2)->nullable();
            $table->double('value_card', 10,2)->nullable();
            $table->text('observation')->nullable();

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
        Schema::dropIfExists('balances');
    }
}
