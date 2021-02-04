<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnsBodyItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('returns_body_item', function (Blueprint $table) {
            $table->id('id');
            $table->integer('returns_header_id')->nullable();
            $table->string('item_description')->nullable();
            $table->decimal('cost', 11, 2)->nullable();
            $table->integer('quantity')->default(1)->nullable();
            $table->longText('problem_details')->nullable();
            $table->longText('problem_details_other')->nullable();
            $table->string('serialize')->default(1)->nullable();
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
        Schema::dropIfExists('returns_body_item');
    }
}
