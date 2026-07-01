# Fix images on Namecheap (elixiira.online)

## Root cause (from server log)

```
/home/elixdlmq/elixira/          ← Laravel app + old upload path
/home/elixdlmq/public_html/      ← Website document root (what browsers hit)
```

Browser requests: `https://elixiira.online/storage/items/photo.jpg`  
Apache looks in: **`/home/elixdlmq/public_html/storage/`**  
Laravel was saving to: **`/home/elixdlmq/elixira/public/storage/`**  

Files were in the wrong folder → broken images.

## Fix (do this on the server)

### 1) Add to `.env` on server

```env
APP_URL=https://elixiira.online
FILESYSTEM_DISK=public
PUBLIC_STORAGE_PATH=/home/elixdlmq/public_html/storage
```

### 2) Upload the updated code

Upload changed files, especially:
- `config/filesystems.php`
- `app/Console/Commands/PublishStorageCommand.php`
- `app/Console/Commands/StorageDoctorCommand.php`

### 3) Copy existing images to public_html

**Option A — Terminal (recommended)**

```bash
cd ~/elixira
php artisan config:clear
php artisan storage:publish --force
php artisan storage:doctor
```

**Option B — File Manager / FTP**

Copy all folders from your PC:

```
storage/app/public/items/
storage/app/public/brands/
...
```

Into server folder:

```
/home/elixdlmq/public_html/storage/
```

Final structure example:

```
public_html/
├── index.php
├── .htaccess
└── storage/
    └── items/
        └── your-image.jpg
```

### 4) Permissions

```bash
chmod -R 755 ~/public_html/storage
find ~/public_html/storage -type f -exec chmod 644 {} \;
```

### 5) Test in browser

Open directly (use real filename from database):

```
https://elixiira.online/storage/items/FILENAME.jpg
```

If this URL shows the image, product pages will work.

## After fix

- **New uploads** go directly to `public_html/storage/` (no symlink).
- **Old path** `storage/app/public/` is only a legacy source for `storage:publish`.

## Troubleshooting

| Symptom | Fix |
|---------|-----|
| 404 on `/storage/items/x.jpg` | File not in `public_html/storage/items/` — run publish or FTP upload |
| Upload works but still 404 | `PUBLIC_STORAGE_PATH` missing in `.env` — run `config:clear` |
| `storage:doctor` says MISSING | Wrong path or files not copied |
| 403 on storage folder | Fix permissions to 755/644 |

## Verify command output

```bash
php artisan storage:doctor
```

Should show:

- `Public disk root: /home/elixdlmq/public_html/storage`
- `File on disk: FOUND`
