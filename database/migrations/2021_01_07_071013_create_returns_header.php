<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnsHeader extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('returns_header', function (Blueprint $table) {
            $table->id('id');
            $table->string('returns_status')->nullable();
            $table->string('returns_status_1')->nullable();
            $table->string('return_reference_no')->nullable();
            $table->string('store')->nullable();
            $table->string('customer_last_name')->nullable();
            $table->string('customer_first_name')->nullable();
            $table->text('address')->nullable();
            $table->string('email_address')->nullable();
            $table->string('contact_no')->nullable();
            $table->string('order_no')->nullable();
            $table->string('purchase_location')->nullable();
            $table->date('purchase_date')->nullable();
            $table->string('mode_of_payment')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_no')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('items_included')->nullable();
            $table->string('items_included_others')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('returns_header');
    }
}
