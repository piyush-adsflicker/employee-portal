<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organization_files', function (Blueprint $table) {
            $table->dropColumn('visibility_type');
        });

        Schema::table('organization_files', function (Blueprint $table) {
            $table->string('visibility_type')->default('Global')->after('category'); // 'Global' or 'Restricted'
        });
    }

    public function down(): void
    {
        Schema::table('organization_files', function (Blueprint $table) {
            $table->dropColumn('visibility_type');
        });
    }
};
