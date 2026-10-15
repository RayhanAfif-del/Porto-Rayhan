<?php

namespace App\Console\Commands;

use App\Models\SiteSetting;
use Database\Seeders\SiteSettingSeeder;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

#[Signature('settings:sync')]
#[Description('Sinkronisasi default setting portofolio ke database tanpa menimpa nilai yang sudah ada')]
class SyncSettingsCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Memulai sinkronisasi setting...');
        $defaults = SiteSettingSeeder::defaultSettings();
        $synced = 0;

        foreach ($defaults as $item) {
            $setting = SiteSetting::firstOrNew(['key' => $item['key']]);

            if (! $setting->exists) {
                $setting->fill($item)->save();
                $this->line("<info>+ Ditambahkan:</info> {$item['key']}");
                $synced++;
            }

            Cache::forget("site_setting.{$item['key']}");
        }

        $this->info("Sinkronisasi selesai. {$synced} setting baru ditambahkan.");

        return self::SUCCESS;
    }
}
