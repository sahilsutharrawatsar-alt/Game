<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('role')->default('user')->index()->after('password');
            $table->string('api_token', 80)->nullable()->unique()->after('remember_token');
            $table->string('google_id')->nullable()->index()->after('api_token');
            $table->string('avatar')->nullable()->after('google_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'role', 'api_token', 'google_id', 'avatar']);
        });
    }
};
