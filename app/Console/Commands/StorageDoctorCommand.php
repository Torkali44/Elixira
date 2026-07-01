<?php

namespace App\Console\Commands;

use App\Models\Item;
use App\Support\StorageUrl;
use Illuminate\Console\Command;

class StorageDoctorCommand extends Command
{
    protected $signature = 'storage:doctor';

    protected $description = 'Diagnose upload paths and sample product image availability';

    public function handle(): int
    {
        $diskRoot = (string) config('filesystems.disks.public.root');
        $laravelPublicStorage = public_path('storage');
        $legacyStorage = storage_path('app/public');

        $this->line('APP_URL: '.config('app.url'));
        $this->line('Laravel base path: '.base_path());
        $this->line('Laravel public_path(): '.public_path());
        $this->line('Public disk root (PUBLIC_STORAGE_PATH): '.$diskRoot);
        $this->line('Disk root writable: '.(is_writable($diskRoot) || (is_dir($diskRoot) && is_writable(dirname($diskRoot))) ? 'yes' : 'NO'));
        $this->line('Legacy storage/app/public files: '.$this->countFiles($legacyStorage));
        $this->line('Laravel public/storage files: '.$this->countFiles($laravelPublicStorage));
        $this->line('Web disk root files: '.$this->countFiles($diskRoot));

        if (realpath($diskRoot) !== realpath($laravelPublicStorage) && is_dir($laravelPublicStorage)) {
            $this->warn('PUBLIC_STORAGE_PATH is NOT Laravel public/storage.');
            $this->warn('This is correct on Namecheap when document root is public_html.');
            $this->line('Run: php artisan storage:publish --force');
        }

        $sample = Item::query()->whereNotNull('image')->where('image', '!=', '')->first();

        if ($sample) {
            $this->newLine();
            $this->line('Sample item #'.$sample->id.': '.$sample->local_name);
            $this->line('DB path: '.$sample->image);
            $this->line('URL: '.StorageUrl::asset($sample->image));
            $this->line('Expected file: '.$diskRoot.'/'.ltrim($sample->image, '/'));
            $exists = StorageUrl::exists($sample->image);
            $this->line('File on disk: '.($exists ? 'FOUND' : 'MISSING'));

            if (! $exists) {
                $this->newLine();
                $this->error('Image file missing. Upload files then run: php artisan storage:publish --force');

                return self::FAILURE;
            }
        } else {
            $this->warn('No items with images in database.');
        }

        $this->info('Storage configuration looks OK.');

        return self::SUCCESS;
    }

    private function countFiles(string $path): int
    {
        if (! is_dir($path)) {
            return 0;
        }

        $count = 0;

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $count++;
            }
        }

        return $count;
    }
}
