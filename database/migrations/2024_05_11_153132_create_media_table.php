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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            /*
                name string
                path string
                mime string
                size string
                tree_id bigint [ref: - trees.id]
            */
            $table->string('name')->comment('ファイル名');
            $table->string('path')->comment('ファイルパス');
            $table->string('mime')->comment('MIMEタイプ');
            $table->string('size')->comment('ファイルサイズ');
            $table->foreignId('tree_id')->nullable()->constrained('trees')->comment('ツリーID');
            $table->foreignId('format_id')->nullable()->constrained('templates')->onDelete('cascade')->comment('フォーマットID');
            $table->foreignId('src_id')->nullable()->constrained('templates')->onDelete('cascade')->comment('ソースID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
