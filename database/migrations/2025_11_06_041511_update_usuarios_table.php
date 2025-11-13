<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->string('google_id')->nullable()->after('email');
            $table->string('google2fa_secret')->nullable()->after('password');
            $table->boolean('is_2fa_enabled')->default(false)->after('google2fa_secret');
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn(['google_id', 'google2fa_secret', 'is_2fa_enabled']);
        });
    }
};
