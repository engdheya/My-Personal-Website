<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_ar')->nullable();
            $table->string('slug')->unique()->index();
            $table->text('short_description')->nullable();
            $table->text('short_description_ar')->nullable();
            $table->longText('description');
            $table->longText('description_ar')->nullable();
            $table->text('problem')->nullable();
            $table->text('problem_ar')->nullable();
            $table->text('solution')->nullable();
            $table->text('solution_ar')->nullable();
            $table->string('main_image')->nullable();
            $table->json('gallery')->nullable();
            $table->string('technologies')->nullable();
            $table->string('category')->nullable()->index();
            $table->string('github_url')->nullable();
            $table->string('live_url')->nullable();
            $table->string('status')->default('Completed')->index();
            $table->string('project_date')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->json('features')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('og_image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
