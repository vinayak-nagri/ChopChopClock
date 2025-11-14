# 🕒 ChopChopClock

A full-stack **Pomodoro timer web app** built with **Laravel** and **vanilla JavaScript**, designed to help users track focused work sessions, breaks, and long-term productivity streaks.

---

## Overview

**ChopChopClock** is a personal productivity tool implementing the classic Pomodoro Technique.  
It allows logged-in users to start, pause, resume, cancel, and finish timer sessions while keeping a daily and historical record of completed work cycles.

The app emphasizes:
- Real-time timer updates handled via JavaScript.
- RESTful Laravel backend endpoints for each timer action.
- Clean, minimal UI powered by Tailwind CSS.
- Persistent user settings and metrics tracking.

---

## Features

### Core Timer
- Start, pause, resume, cancel, and finish sessions asynchronously.
- Auto-switches between *Work* and *Break* cycles.
- Plays a desktop notification sound on completion.

### Dashboard
- Displays active timer, daily completed sessions, and total focused time.
- Metrics update dynamically.
- Preset buttons for **Work**, **Short Break**, and **Long Break** durations.

### History
- Paginated record of all past *Work* sessions.
- Toggle between *Completed* and *Cancelled* sessions.
- Daily streak calculation for consecutive productivity days.
- Summary cards for total hours, total days logged, and current streak.

### Settings
- Customize durations for Work, Short Break, and Long Break.
- Changes persist per user.

### Authentication
- Built with Laravel’s authentication scaffolding.
- Routes protected to ensure only logged-in users can access dashboards and API endpoints.

---

## Tech Stack

| Layer | Technology                    |
|-------|-------------------------------|
| **Frontend** | Vanilla JS, Tailwind CSS, Blade Templates |
| **Backend** | Laravel 12 (PHP 8.2)          |
| **Database** | MySQL                         |
| **Audio** | Howler.js (for notification sounds) |
| **Package Manager** | Composer / npm                |
| **Build Tool** | Vite                          |

---

## Installation & Setup

### 1. Clone the repository
```bash
  git clone https://github.com/vinayak-nagri/ChopChopClock.git
  cd ChopChopClock
```

### 2. Install Dependencies

```bash
  composer install
  npm install
```

### 3. Configure Environment

```bash
  cp .env.example .env
  php artisan key:generate
```

Edit .env and update your database credentials.

### 4. Run migrations

```bash
  php artisan migrate
```

### 5. Build frontend assets

```bash
  npm run dev
```

### 6. Start the local server

```bash
  php artisan serve
```

Visit the local server URL displayed in your terminal (usually http://127.0.0.1:8000 or http://localhost:8000).

## Build Notes (for CI)
- Node >= 18
- PHP 8.2
- Run: `npm ci && npm run build`
- Run: `composer install --no-dev --optimize-autoloader`

### Author

Vinayak Nagri 
<br><br>ChopChopClock - built as part of a full-stack learning project exploring Laravel, RESTful design, and asynchronous front-end integration.
