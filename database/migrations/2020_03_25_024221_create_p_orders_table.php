<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('p_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('po_number')->unique();
            $table->string('description');
            $table->string('status');
            $table->timestamps();
            $table->unsignedBigInteger('pr_id')->nullable();

            $table->index('pr_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('p_orders');
    }
}
