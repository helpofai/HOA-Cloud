# 🚀 Shared Hosting Queue Automation

This document explains how the HOA Cloud queue system operates on shared hosting environments where terminal access (SSH) is restricted.

## 🛠️ How it Works

The system uses a **Self-Starting Worker** architecture. Instead of requiring a persistent `php artisan queue:work` process, the application triggers a temporary background worker automatically.

1.  **Auto-Trigger:** When a file upload completes, the `UploadController` calls the `SharedHostingQueueService`.
2.  **Background Process:** The service spawns a background PHP process using `exec()` (Linux) or `popen` (Windows).
3.  **Smart Shutdown:** The worker runs with `--stop-when-empty`, meaning it processes all pending jobs (Merge Chunks, Fetch Metadata) and then terminates itself to save server resources.
4.  **Concurrency Lock:** A cache-based lock prevents multiple workers from running simultaneously, protecting your hosting account from being suspended for high CPU usage.

---

## 📅 Mandatory Cron Job Setup

To ensure background tasks are processed even if a trigger fails, you **MUST** set up a single Cron Job in your hosting control panel (CPanel, DirectAdmin, Plesk).

### CPanel Command
Add this to your "Cron Jobs" section, set to run **Every Minute** (`* * * * *`):

```bash
/usr/local/bin/php /home/username/public_html/artisan schedule:run >> /dev/null 2>&1
```

*Note: Replace `/home/username/public_html/` with the actual absolute path to your project root.*

---

## 🕹️ Manual Control Commands

If you need to manually trigger or manage the queue via a web-shell or terminal, use these commands:

### 1. Force Start the Worker
If jobs are stuck and you want to force a new worker to start immediately:
```bash
php artisan queue:shared-start
```

### 2. Process One Job (Debug Mode)
To see errors directly in your terminal/console:
```bash
php artisan queue:work --once
```

### 3. Clear Failed Jobs
If a video merge failed (usually due to disk space), clear the records after fixing the issue:
```bash
php artisan queue:failed
php artisan queue:retry all
```

---

## ⚠️ Troubleshooting

- **Symlink Error:** If you don't see your files, ensure the storage link is created:
  `php artisan storage:link`
- **Memory Limits:** If the worker crashes on large files, increase the memory limit in your `php.ini` or ask your host to increase `max_execution_time`.
- **Proc_open/Exec Disabled:** Ensure your hosting provider has not disabled `exec()` or `proc_open()` in the PHP configuration.
