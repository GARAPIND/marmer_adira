<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('photo_proses_pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->cascadeOnDelete();
            $table->string('status_target', 30);
            $table->string('photo_path');
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();

            $table->index(['pesanan_id', 'status_target']);
        });

        if (Schema::hasColumns('pesanan', ['foto_dikerjakan', 'foto_selesai'])) {
            $rows = DB::table('pesanan')
                ->select('id', 'foto_dikerjakan', 'foto_selesai', 'created_at', 'updated_at')
                ->get();

            foreach ($rows as $row) {
                $this->migratePhotosForStatus($row, 'Dikerjakan', $row->foto_dikerjakan ?? null);
                $this->migratePhotosForStatus($row, 'Selesai', $row->foto_selesai ?? null);
            }

            Schema::table('pesanan', function (Blueprint $table) {
                $table->dropColumn(['foto_dikerjakan', 'foto_selesai']);
            });
        }
    }

    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            if (!Schema::hasColumn('pesanan', 'foto_dikerjakan')) {
                $table->json('foto_dikerjakan')->nullable()->after('gambar_referensi');
            }

            if (!Schema::hasColumn('pesanan', 'foto_selesai')) {
                $table->json('foto_selesai')->nullable()->after('foto_dikerjakan');
            }
        });

        if (Schema::hasTable('photo_proses_pesanan')) {
            $groupedPhotos = DB::table('photo_proses_pesanan')
                ->orderBy('pesanan_id')
                ->orderBy('status_target')
                ->orderBy('urutan')
                ->orderBy('id')
                ->get()
                ->groupBy('pesanan_id');

            foreach ($groupedPhotos as $pesananId => $photos) {
                $fotoDikerjakan = $photos
                    ->where('status_target', 'Dikerjakan')
                    ->pluck('photo_path')
                    ->values()
                    ->all();

                $fotoSelesai = $photos
                    ->where('status_target', 'Selesai')
                    ->pluck('photo_path')
                    ->values()
                    ->all();

                DB::table('pesanan')
                    ->where('id', $pesananId)
                    ->update([
                        'foto_dikerjakan' => empty($fotoDikerjakan) ? null : json_encode($fotoDikerjakan),
                        'foto_selesai' => empty($fotoSelesai) ? null : json_encode($fotoSelesai),
                    ]);
            }
        }

        Schema::dropIfExists('photo_proses_pesanan');
    }

    private function migratePhotosForStatus(object $row, string $statusTarget, mixed $rawPhotos): void
    {
        $photos = $this->normalizePhotos($rawPhotos);

        foreach ($photos as $index => $photoPath) {
            DB::table('photo_proses_pesanan')->insert([
                'pesanan_id' => $row->id,
                'status_target' => $statusTarget,
                'photo_path' => $photoPath,
                'urutan' => $index + 1,
                'created_at' => $row->created_at ?? now(),
                'updated_at' => $row->updated_at ?? now(),
            ]);
        }
    }

    private function normalizePhotos(mixed $rawPhotos): array
    {
        if (is_string($rawPhotos)) {
            $decoded = json_decode($rawPhotos, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $rawPhotos = $decoded;
            } elseif (trim($rawPhotos) !== '') {
                $rawPhotos = [$rawPhotos];
            } else {
                $rawPhotos = [];
            }
        }

        if (!is_array($rawPhotos)) {
            return [];
        }

        return array_values(array_filter($rawPhotos, fn ($photo) => is_string($photo) && trim($photo) !== ''));
    }
};
