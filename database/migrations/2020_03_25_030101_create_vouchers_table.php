<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('description');
            $table->string('status');
            $table->timestamps();
            $table->string('remarks')->nullable();
            $table->string('type')->nullable();
            $table->unsignedDecimal('amount', 12, 2)->nullable()->default(0);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('po_id')->nullable();
            $table->unsignedBigInteger('to_id')->nullable();

            $table->index('user_id');
            $table->index('po_id');
            $table->index('to_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vouchers');
    }
}
