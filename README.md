````md
# FreshCheck — Food Freshness Timer (Flutter + PHP API + Online MySQL)

FreshCheck helps users track when food was opened and how many **safe days** remain.

It includes:

- **Phase 1 (Calculator):** Choose item type + opened date → shows expiry + countdown
- **Phase 2 (My Fridge):** Save items to an online database using a backend API (CRUD)

---

## ✅ Requirements Coverage

- ✅ Full-stack mobile project (Flutter front-end + backend services + online DB)
- ✅ At least 2 screens:
    - Freshness Calculator
    - My Fridge (List)
    - Add/Edit Item (Form)
- ✅ Backend published online (Render) — **NO localhost**
- ✅ Online DB published online (Aiven MySQL)
- ✅ Backend code uploaded
- ✅ SQL statements uploaded
- ✅ GitHub repository submission (ZIP not accepted as main submission)

---

## 🧱 Tech Stack

### Frontend (Mobile)

- Flutter (Dart)
- `http` package for API requests
- Material UI (Material 3)

### Backend (API)

- PHP REST-style API (CRUD endpoints)
- PDO for database access
- Deployed on Render using Docker + Apache

### Online Database

- Aiven MySQL (cloud hosted)

---

## 📱 Screens / Features

### 1) Freshness Calculator (Phase 1)

- Select food type (Milk/Cheese/Chicken/Leftovers/Custom)
- Pick opened date
- Displays:
    - Expiry date
    - Countdown time left
    - Status badge: **Fresh / Soon / Expired**
- Button to add the item directly to the fridge list

### 2) My Fridge (Phase 2)

- Loads items from online API
- Status color indicator:
    - Green = Fresh
    - Orange = Soon
    - Red = Expired
- CRUD:
    - Add item
    - Edit item
    - Delete item

### 3) Add/Edit Item Screen

- Name
- Opened date
- Safe days
- Note (optional)
- Photo URL (optional)

---

## 🌍 Online URLs (NO localhost)

### ✅ Backend (Render)

- **API Home:** https://freshcheck-m2q0.onrender.com  
  Returns JSON like:
  ```json
  {"status":"ok","message":"FreshCheck API is running"}
````

* **API Base URL (used by Flutter):**
  [https://freshcheck-m2q0.onrender.com/api/index.php](https://freshcheck-m2q0.onrender.com/api/index.php)

### ✅ API Endpoints

* `GET    /items`
* `POST   /items`
* `PUT    /items/{id}`
* `DELETE /items/{id}`

Example:

* [https://freshcheck-m2q0.onrender.com/api/index.php/items](https://freshcheck-m2q0.onrender.com/api/index.php/items)

---

## 🗃️ Online Database (Aiven MySQL)

* Hosted on Aiven MySQL (cloud)
* Schema file:

    * `backend/sql/schema.sql`

Tables:

* `users(id, email, password_hash)`
* `items(id, user_id, name, opened_at, safe_days, note, photo_url, created_at)`

---

## 📂 Project Structure

```text
freshcheck/
  lib/
    main.dart
    fridge_list_screen.dart
    add_item_screen.dart

  backend/
    api/
      index.php
    config/
      db.php
    sql/
      schema.sql
    Dockerfile
    .htaccess
    index.php
```

---

## ▶️ Run Flutter App Locally

### 1) Install dependencies

```bash
flutter pub get
```

### 2) Run

```bash
flutter run
```

> The app is configured to use the ONLINE API:
> `https://freshcheck-m2q0.onrender.com/api/index.php`

---

## 🧪 Optional: Run Backend Locally (XAMPP)

> Final submission uses the online Render link. Local is only for testing.

1. Copy backend folder to:

* `C:\xampp\htdocs\freshcheck_backend\`

2. Start XAMPP:

* Apache ✅
* MySQL ✅

3. Open:

* [http://localhost/freshcheck_backend/api/index.php](http://localhost/freshcheck_backend/api/index.php)

---

## 🧾 SQL Statements

SQL file path:

* `backend/sql/schema.sql`

This file includes table creation statements for `users` and `items`.

---

## ✅ Submission Info

* **GitHub repo:
  ** [https://github.com/mohamadalijomaa25/freshcheck.git](https://github.com/mohamadalijomaa25/freshcheck.git)
* **Backend URL:** [https://freshcheck-m2q0.onrender.com](https://freshcheck-m2q0.onrender.com)
* **API Base URL:
  ** [https://freshcheck-m2q0.onrender.com/api/index.php](https://freshcheck-m2q0.onrender.com/api/index.php)
* **SQL file:** `backend/sql/schema.sql`
* **Backend code:** `backend/`

---

## 🔮 Future Improvements

* Add authentication (signup/login) and user-specific items
* Photo upload instead of URL
* Notifications for expiring items
* Filtering/sorting (Expired / Soon / Fresh)

```

If you still see text “escaping” in GitHub, tell me what editor you pasted into (Android Studio / GitHub web editor), and I’ll tell you the exact safe paste method.
```
