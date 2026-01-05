# FreshCheck — Food Freshness Timer (Flutter + PHP API + Online MySQL)

FreshCheck helps users track when food was opened and how many “safe days” remain.
It has:
- **Phase 1 (Calculator):** Choose food type + opened date → shows expiry + countdown.
- **Phase 2 (My Fridge):** Save items to an online database using a backend API (CRUD).

---

## ✅ Project Requirements Checklist
- ✅ Full stack mobile app (Flutter front-end)
- ✅ At least 2 screens:
    - Freshness Calculator
    - My Fridge List
    - Add / Edit Item screen
- ✅ Backend published online (Render)
- ✅ Online database published online (Aiven MySQL)
- ✅ GitHub repo (ZIP not required)
- ✅ SQL statements included (schema.sql)
- ✅ Backend code included (backend folder)

---

## 🧱 Tech Stack
### Frontend (Mobile)
- Flutter (Dart)
- HTTP networking (`http` package)
- Material 3 UI

### Backend (API)
- PHP (REST-like API)
- Apache + Docker (Render deployment)
- PDO for database connection

### Online Database
- Aiven MySQL (cloud hosted)

---

## 📱 Screens
**Freshness Calculator**
- Choose food type (Milk, Cheese, Chicken, Leftovers, Custom)
- Select opened date
- Shows:
    - Expiry date
    - Countdown
    - Status badge (Fresh / Soon / Expired)

**My Fridge**
- Fetches saved items from the online API
- Shows warning color:
    - Green = fresh
    - Orange = soon
    - Red = expired
- Create, update, delete items

**Add/Edit Item**
- name, opened date, safe days, note, optional photo URL
- preview badge and expiry

---

## 🌍 Online Deployment URLs (NO localhost)
### ✅ Backend API (Render)
Base URL:
- https://freshcheck-m2q0.onrender.com/api/index.php

Test:
- https://freshcheck-m2q0.onrender.com

Endpoints:
- GET    `/items`
- POST   `/items`
- PUT    `/items/{id}`
- DELETE `/items/{id}`

### ✅ Online Database (Aiven MySQL)
Hosted on Aiven (cloud MySQL).
Schema is in:
- `backend/sql/schema.sql`

---

## 🗃️ Database Schema (SQL)
File:
- `backend/sql/schema.sql`

Tables:
- `users(id, email, password_hash)`
- `items(id, user_id, name, opened_at, safe_days, note, photo_url, created_at)`

---

## 🔌 Flutter App → API Connection
The Flutter app uses this constant:
```dart
const String kApiBaseUrl = "https://freshcheck-m2q0.onrender.com/api/index.php";
