<?php

use App\Models\PaymentHistory;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payment_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(User::class);
            $table->string('payment_provider');
            $table->string('payment_method');
            $table->string('currency');
            $table->float('quantity',8,0);
            $table->float('price',8,2);
            $table->float('usd_rate',8,2);
            $table->float('total',8,2);
            $table->json('payment_instruction');
            $table->json('payment_response')->nullable();
            $table->dateTime('expired_at');
            $table->string('status')->default(PaymentHistory::STATUS_WAITING_FOR_PAYMENT);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_histories');
    }
};
