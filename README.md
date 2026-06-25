# Car Booking System - Full Web

PHP + PostgreSQL car booking website.

## Features

- Register / Login / Logout
- Home page with 3 car types
- Booking form (Luxury, Premium, Air Bus)
- Admin dashboard, users, vehicles
- Data saved in PostgreSQL

## Setup (Laragon — local)

1. Copy folder to `C:\laragon\www\`
2. Edit `config.php` (PostgreSQL password) if needed
3. Start Laragon: Apache + PostgreSQL
4. Open `http://localhost/Car_Booking_Sytem-main/`

Database tables are created automatically on first use.

**Default admin:** `admin` / `admin123`

---

## Deploy on Render

### What you need

- [Render](https://render.com) account (free tier works for testing)
- GitHub repo with this project pushed

### Steps

1. **Push code to GitHub**
   ```bash
   git init
   git add .
   git commit -m "Prepare for Render deploy"
   git remote add origin https://github.com/YOUR_USER/YOUR_REPO.git
   git push -u origin main
   ```

2. **Create Blueprint on Render**
   - Go to [dashboard.render.com](https://dashboard.render.com)
   - Click **New +** → **Blueprint**
   - Connect your GitHub repo
   - Render reads `render.yaml` and creates:
     - **Web service** `premium-bus` (Docker / PHP)
     - **PostgreSQL** `premium-bus-db`

3. **Deploy**
   - Click **Apply** and wait for build (~5–10 min first time)
   - Open your URL: `https://premium-bus.onrender.com` (name may vary)

4. **Login**
   - Register a user, or use admin: `admin` / `admin123` (created on first DB connect)

### Render notes

| Item | Detail |
|------|--------|
| Free web | Sleeps after 15 min idle (~30–60s cold start) |
| Free Postgres | **Expires after 30 days** — upgrade to paid (~$7/mo) for production |
| Uploaded images | Stored on ephemeral disk — may reset on redeploy; use existing `image/` paths for stable assets |
| Env vars | `DATABASE_URL` is set automatically from `render.yaml` |

### Optional env vars (Render dashboard → Environment)

| Variable | Purpose |
|----------|---------|
| `APP_BASE_URL` | OAuth callback base URL |
| `GOOGLE_ENABLED` | `true` to enable Google login |
| `GOOGLE_CLIENT_ID` | Google OAuth client ID |
| `GOOGLE_CLIENT_SECRET` | Google OAuth secret |

---

## Main Files

- `config.php` — database + OAuth settings (reads `DATABASE_URL` on Render)
- `db.php` — connect + migrations + seed data
- `Dockerfile` — PHP 8.2 Apache for Render
- `render.yaml` — Render Blueprint (web + Postgres)
- `health.php` — health check endpoint

