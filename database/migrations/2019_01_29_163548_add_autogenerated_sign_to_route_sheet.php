<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAutogeneratedSignToRouteSheet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('route_sheets', function (Blueprint $table): void {
            $table->tinyInteger('autogenerated')
                ->after('active_to')
                ->default(0)
                ->comment('Whether route sheet was generated automatically or not');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('route_sheets', function (Blueprint $table): void {
            $table->dropColumn('autogenerated');
        });
    }
}
