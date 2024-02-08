<?php

declare(strict_types=1);

use App\Models\Account;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Account::class,'sender_id')->nullable(false);
            $table->foreignIdFor(Account::class,'receiver_id')->nullable(false);
            $table->integer('amount')->nullable(false);
            $table->unsignedTinyInteger('status')->nullable(true);
            $table->date('scheduled_to')->nullable(true);
            $table->dateTime('charged_at')->nullable(true);
            $table->string('error')->nullable(true);
            $table->timestamps();
            $table->index(['status', 'scheduled_to']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
