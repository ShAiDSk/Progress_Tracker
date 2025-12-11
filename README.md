
<h1 align="center">
  <img src="https://readme-typing-svg.herokuapp.com?size=28&duration=4000&color=3FA9F5&center=true&vCenter=true&width=600&lines=Progress+Tracker;Build+Habits.;Track+Progress.+Win+Consistently." />
</h1>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-f9322c?style=for-the-badge&logo=laravel&logoColor=white" />
  <img src="https://img.shields.io/badge/TailwindCSS-3.x-38bdf8?style=for-the-badge&logo=tailwindcss&logoColor=white" />
  <img src="https://img.shields.io/badge/Vite-5.x-646cff?style=for-the-badge&logo=vite&logoColor=white" />
  <img src="https://img.shields.io/badge/Status-Active-brightgreen?style=for-the-badge" />
</p>

# Progress Tracker 🎯

A modern, gamified goal tracking system built with Laravel 11. Featuring a premium dark UI, GitHub-style contribution heatmaps, automated email notifications, and a badge reward system to keep you motivated.

# 📸 Screenshots

### 🏡 Homepage  
<img src="/home_page.png"/>

---

### 🔑 Login Page  
<img src="/signin_page.png"/>

---

### 🔑 Register Page  
<img src="/Register_page.png"/>

---

### 📊 Home page 
<img src="/home_page.png"/>

---

### 📊 Dashboard (Dark Mode & Heatmap)  
<img src="/Dashboard_page.png"/>

---

### 🏆 Gamification (Unlocked Badges)
<img src="/Achievements_section.png"/>

---

### 📊 user Profile 
<img src="/profile_page.png"/>

---

### 🎯 Goals List (With Sub-tasks)  
<img src="/goal_page.png"/>

---

### 📊  create goals page
<img src="/create_goal.png"/>

---

### 📊 Archived Goals page  
<img src="/Archived_Goals_page.png"/>

---

# Demo video 
<video controls src="/Progress_Tracker_Demo_video_comp.mp4" title="Title"></video>

---

## 🔥 Key Features
```
- **✅ Goal Management**: Create, edit, delete, and organize goals.
- **📂 Archive System**: Hide completed or paused goals to keep your dashboard clean, with full restore capability.
- **📋 Sub-tasks**: Break down large goals into smaller, manageable checklists.
- **📅 Activity Heatmap**: Visualize your consistency with a 365-day GitHub-style contribution graph.
- **🏆 Gamification**: Unlock badges for milestones (e.g., "First Steps", "Goal Crusher", "Early Bird").
- **📧 Email Notifications**: Receive "Goal Crushed" emails automatically when you complete a target.
- **👤 User Profiles**: Customize your identity with Profile Picture uploads and Bio.
- **🌙 Premium UI**: Fully responsive Dark Mode design with glass-morphism effects.
```
## 🛠 Tech Stack
```
| Layer | Technologies |
|-------|-------------|
| Frontend | TailwindCSS 3.x, Alpine.js 3.x, Vite 5.x |
| Backend | Laravel 11, PHP 8.2+, Eloquent ORM |
| Authentication | Laravel Breeze |
| Notifications | Laravel Mailables (Log/SMTP) |
| Database | MySQL 8.0+ / SQLite |
| Architecture | MVC, Event-Driven (Listeners for Badges/Emails) |
```

## Project Structure

```
progress-tracker/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── GoalController.php      # CRUD operations for goals
│   │   │   ├── DashboardController.php # Dashboard stats and overview
│   │   │   └── ProfileController.php   # User profile management
│   │   └── Requests/
│   │       ├── StoreGoalRequest.php    # Validation for creating goals
│   │       └── UpdateGoalRequest.php   # Validation for updating goals
│   ├── Models/
│   │   ├── User.php                    # User model with relationships
│   │   ├── Goal.php                    # Goal model with soft deletes
│   │   └── Streak.php                  # Streak tracking model
│   └── Services/
│       └── StreakService.php           # Business logic for streak calculation
├── database/
│   ├── migrations/
│   │   ├── 2024_xx_create_goals_table.php
│   │   └── 2024_xx_create_streaks_table.php
│   └── seeders/
│       └── GoalSeeder.php              # Sample data for development
├── resources/
│   ├── views/
│   │   ├── dashboard.blade.php         # Main dashboard
│   │   ├── goals/
│   │   │   ├── index.blade.php        # Goals list
│   │   │   ├── create.blade.php       # Create goal form
│   │   │   ├── edit.blade.php         # Edit goal form
│   │   │   └── show.blade.php         # Single goal view
│   │   └── layouts/
│   │       └── app.blade.php          # Main layout with navigation
│   ├── css/
│   │   └── app.css                    # Tailwind and custom styles
│   └── js/
│       ├── app.js                     # Alpine.js components
│       └── streak.js                  # Streak calculation logic
├── routes/
│   └── web.php                        # Application routes
├── tests/
│   ├── Feature/
│   │   ├── GoalManagementTest.php     # Feature tests for goals
│   │   └── StreakCalculationTest.php  # Feature tests for streaks
│   └── Unit/
│       └── StreakServiceTest.php      # Unit tests for streak service
└── README.md
```


## 🗄️ Database Schema

### Users Table
- `id`, `name`, `email`, `password`
- `avatar` - Profile picture path (nullable)
- `bio` - User biography (nullable)

### Goals Table
- `id`, `user_id`
- `title`, `description`
- `target_value`, `current_value`
- `status` - Enum: 'active', 'completed', 'archived'
- `completed_at` - Timestamp for heatmap logic
- `deleted_at` - Soft deletes

### SubTasks Table
- `id`, `goal_id`
- `title`
- `is_completed` - Boolean

### Achievements Table
- `id`
- `name` - e.g., "Early Bird"
- `icon` - Emoji or Icon class
- `description` - Criteria description

### Achievement_User (Pivot)
- `user_id`, `achievement_id`
- `unlocked_at` - When the user earned the badge

## 🚀 Installation & Setup

### Prerequisites
- PHP 8.2 or higher
- Composer 2.x
- Node.js 18.x or higher
- Database (MySQL or SQLite)

### Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/ShAiDSk/Progress_Tracker.git
   cd Progress_Tracker

2.  **Install Dependencies**

    ```bash
    composer install
    npm install
    ```

3.  **Environment Setup**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4.  **Configure Database**
    Edit `.env` to match your database. For SQLite:

    ```env
    DB_CONNECTION=sqlite
    # Remove DB_HOST, DB_PORT, etc.
    ```

    Create the file:

    ```bash
    touch database/database.sqlite
    ```

5.  **Link Storage (Important for Avatars)**

    ```bash
    php artisan storage:link
    ```

6.  **Run Migrations & Seeders**

    ```bash
    php artisan migrate --seed
    ```

7.  **Build Frontend & Start Server**

    ```bash
    npm run build
    php artisan serve
    ```

    Visit `http://localhost:8000` in your browser.

## 🧪 Testing

Run the full test suite to ensure everything is working:

```bash
php artisan test
```

## 📧 Email Testing (Local)

To test email notifications without a real mail server, set your `.env` to use the log driver:

```env
MAIL_MAILER=log
```

Check `storage/logs/laravel.log` after completing a goal to see the HTML email content.

## 🤝 Contributing

Contributions are welcome\!

1.  Fork the repo.
2.  Create a branch: `git checkout -b feature/cool-new-thing`.
3.  Commit changes: `git commit -m 'Added cool thing'`.
4.  Push to branch: `git push origin feature/cool-new-thing`.
5.  Open a Pull Request.

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://www.google.com/search?q=LICENSE).

-----

# ❤️ Credits

Designed & Developed by **Shaid SK (itz-shaidsk)**

-----

# ⭐ Support

If you find this project helpful or interesting, please give it a **star** on GitHub\! 🌟
Your support keeps the project moving forward 🚀

-----