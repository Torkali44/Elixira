<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PublishStorageCommand extends Command
{
    protected $signature = 'storage:publish {--force : Overwrite existing files}';

    protected $description = 'Copy uploaded files into the web-accessible storage folder (PUBLIC_STORAGE_PATH or public/storage)';

    public function handle(): int
    {
        $target = (string) config('filesystems.disks.public.root');
        $sources = array_values(array_unique(array_filter([
            storage_path('app/public'),
            public_path('storage'),
        ], fn (string $path): bool => is_dir($path) && realpath($path) !== realpath($target))));

        if ($sources === []) {
            $this->warn('No legacy source folders found to publish from.');

            if (! is_dir($target)) {
                File::makeDirectory($target, 0755, true);
                $this->line('Created target: '.$target);
            }

            return self::SUCCESS;
        }

        if (! is_dir($target)) {
            File::makeDirectory($target, 0755, true);
            $this->line('Created target: '.$target);
        }

        $copied = 0;
        $skipped = 0;

        foreach ($sources as $source) {
            $this->line('Source: '.$source);

            foreach (File::allFiles($source) as $file) {
                $relativePath = $file->getRelativePathname();
                $destination = $target.DIRECTORY_SEPARATOR.$relativePath;
                $destinationDir = dirname($destination);

                if (! is_dir($destinationDir)) {
                    File::makeDirectory($destinationDir, 0755, true);
                }

                if (file_exists($destination) && ! $this->option('force')) {
                    $skipped++;

                    continue;
                }

                File::copy($file->getPathname(), $destination);
                $copied++;
            }
        }

        $this->info("Published {$copied} file(s) to {$target}".($skipped > 0 ? " ({$skipped} skipped)" : '').'.');

        return self::SUCCESS;
    }
}
