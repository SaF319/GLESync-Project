<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            // Solo crear columna si no existe
            if (!Schema::hasColumn('usuarios', 'google_id')) {
                $table->string('google_id')->nullable()->after('email');
            }

            if (!Schema::hasColumn('usuarios', 'google2fa_secret')) {
                $table->string('google2fa_secret')->nullable()->after('password');
            }

            if (!Schema::hasColumn('usuarios', 'is_2fa_enabled')) {
                $table->boolean('is_2fa_enabled')->default(false)->after('google2fa_secret');
            }
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            // Solo eliminar columna si existe
            if (Schema::hasColumn('usuarios', 'google_id')) {
                $table->dropColumn('google_id');
            }
            if (Schema::hasColumn('usuarios', 'google2fa_secret')) {
                $table->dropColumn('google2fa_secret');
            }
            if (Schema::hasColumn('usuarios', 'is_2fa_enabled')) {
                $table->dropColumn('is_2fa_enabled');
            }
        });
    }
};
