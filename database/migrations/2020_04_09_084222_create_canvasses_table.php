<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCanvassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('canvasses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('mayors_permit')->nullable();
            $table->string('clearance')->nullable();
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
        Schema::dropIfExists('canvasses');
    }
}
