<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->increments('id');
            $table->text('title');
            $table->date('prod_date')->nullable();
            $table->text('description')->nullable();
            $table->text('photo')->default('https://websoul.pl/blog/wp-content/uploads/2013/06/question-mark1.jpg');
            $table->decimal('score')->default(0);
            $table->integer('number_of_scores')->default(0);
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
        Schema::dropIfExists('movies');
    }
}
