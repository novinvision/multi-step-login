<?php

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
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->fulltext();
            $table->string('company')->nullable();

            $table->string('mobile', 30)->unique();
            $table->string('email')->nullable()->index();

            $table->string('tel', 30)->nullable();

            $table->timestamp('mobile_verified_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();

            $table->set('status', [
                'active',
                'disable',
            ])->index()->default('active');

            $table->string('password');
            $table->rememberToken();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('users');
    }
};
