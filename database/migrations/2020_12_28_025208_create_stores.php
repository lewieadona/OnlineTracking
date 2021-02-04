<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->integer('channels_id');
            $table->integer('store_types_id');
            $table->string('segmentation_code');
            $table->string('store_name');
            $table->string('store_pos_name');
            $table->string('store_bill_to');
            $table->string('store_location');
            $table->enum('store_status', ['ACTIVE', 'INACTIVE'])->default('ACTIVE');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps();
            $table->softDeletesTz();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stores');
    }
}
