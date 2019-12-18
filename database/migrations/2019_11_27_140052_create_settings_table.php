<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table
                ->unsignedBigInteger('program_id')
                ->nullable();

            $table
                ->unsignedBigInteger('language_id')
                ->nullable();

            $table->string('copyright');
            $table->boolean('explicit');
            $table->string('subtitle');
            $table->string('author');
            $table->string('owner_name');

            $table->timestamps();

            $table
                ->foreign('program_id')
                ->references('id')->on('programs')
                ->onDelete('cascade');

            $table
                ->foreign('language_id')
                ->references('id')->on('languages')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
