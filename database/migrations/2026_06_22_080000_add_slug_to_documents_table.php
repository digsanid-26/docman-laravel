<?php

use App\Models\Document;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('title');
        });

        // Populate slug for existing records
        Document::whereNull('slug')->get()->each(function ($doc) {
            $slug = Str::slug($doc->title);
            $original = $slug;
            $count = 1;

            while (Document::where('slug', $slug)->where('id', '!=', $doc->id)->exists()) {
                $slug = $original . '-' . $count++;
            }

            $doc->updateQuietly(['slug' => $slug ?: 'document-' . $doc->id]);
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
