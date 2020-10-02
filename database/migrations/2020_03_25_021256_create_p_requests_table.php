<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('p_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('pr_number')->unique();
            $table->string('description');
            $table->string('purpose');
            $table->string('status');
            $table->timestamps();
            $table->string('type');
            $table->string('remarks')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();

            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('p_requests');
    }
}
