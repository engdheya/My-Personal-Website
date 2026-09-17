<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('title_ar')->nullable();
            $table->string('slug')->unique()->index();
            $table->text('excerpt')->nullable();
            $table->text('excerpt_ar')->nullable();
            $table->longText('content');
            $table->longText('content_ar')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('alt_text')->nullable();
            $table->string('status')->default('published')->index(); // draft, published, scheduled, archived
            $table->timestamp('published_at')->nullable()->index();
            $table->unsignedInteger('reading_time')->default(5);
            $table->string('seo_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('og_image')->nullable();
            $table->string('focus_keyword')->nullable();
            $table->unsignedBigInteger('views_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
