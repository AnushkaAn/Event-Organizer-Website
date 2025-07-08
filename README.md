# 🎓 College Societies Event Organizer Website

This is a full-featured **Event Management Web Application** designed for college societies. It allows an admin to manage societies, auditions, and events, while users can register, log in, and apply to various events or auditions.

🌐 **Live Demo**:  
👉 [Visit Deployed Website](https://app-17f97fdf-26ba-406e-82eb-16ea76650160.cleverapps.io/)

---

## ✨ Features

### 👩‍💼 Admin Panel
- View and manage users
- Create and update societies
- Organize and edit events & auditions
- Access control for user roles

### 👥 User Panel
- Register and log in
- Browse and apply for society auditions
- View event details and announcements

---

## 🛠 Tech Stack

| Layer       | Technology Used            |
|-------------|-----------------------------|
| Frontend    | HTML, CSS, JavaScript       |
| Backend     | PHP (Procedural + MySQLi)   |
| Database    | MySQL (via Clever Cloud)    |
| Hosting     | Clever Cloud (PHP runtime)  |
| Version Control | Git + GitHub             |

---

## 🗂 Project Structure

/
├── admin/ → Admin dashboard, controls
├── user/ → User dashboard, profile, etc.
├── auth/ → Login & registration system
├── assets/ → CSS, JS, images
├── config/ → DB connection (database.php)
├── includes/ → Header, footer, reusable components
├── index.php → Homepage
├── .htaccess → Routing & rewrite rules
└── clevercloud/
└── php.version → Runtime version specification


---

## 🚀 How to Run Locally (For Developers)

1. **Clone the repository**:
   ```bash
   git clone https://github.com/your-username/Event-Organizer-Website.git
   cd Event-Organizer-Website
