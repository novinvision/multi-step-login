<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if(Schema::hasColumn(config("auth.passwords.users.table"), 'user_id') && !Schema::hasColumn(config("auth.passwords.users.table"), 'user_type')){
            Schema::table(config("auth.passwords.users.table"), function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
        }

        if (Schema::hasTable(config("auth.passwords.users.table"))) Schema::table(config("auth.passwords.users.table"), function (Blueprint $table) {
            $table->nullableMorphs('user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
