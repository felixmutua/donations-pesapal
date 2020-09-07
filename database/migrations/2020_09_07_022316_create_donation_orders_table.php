<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonationOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 'donor_name', 'donor_email', 'msisdn', 'amount'
        Schema::create('donation_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id');
            $table->string('tracking_id')->default('');
            $table->string('donor_name');
            $table->string('donor_email');
            $table->string('msisdn');
            $table->string('status');
            $table->double('amount');
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
        Schema::dropIfExists('donation_orders');
    }
}
