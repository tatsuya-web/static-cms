<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('values', function (Blueprint $table) {
            $table->id();
            /*
            * format string
            * name name
            * value text
            * template_id unsignedBigInteger
            * parent_id unsignedBigInteger
            */
            $table->string('format')->comment('テンプレートフォーマット');
            $table->string('name')->comment('名前');
            $table->text('value')->comment('値');
            $table->foreignId('template_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('values')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('values');
    }
};
