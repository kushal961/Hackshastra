# Hackshastra 🏆

**Hackshastra** is a web-based platform for students to form teams for hackathons and competitions.

### **🌐 Live Demo**  
🔗 [Hackshastra](http://healthcareplus.infinityfreeapp.com/)  


## 📌 Features

- 📝 **Student Registration & Login**
- 📢 **Team Request Posting**
- 👀 **Viewing & Managing Team Requests**
- ✅ **Team Request Deletion (Once Filled)**
- 📊 **Dashboard for Students**
- 🗓 **Hackathon Listings & Details Page**

## 🛠️ Tech Stack

- **Frontend:** HTML, CSS, JavaScript, Bootstrap
- **Backend:** PHP
- **Database:** SQL (MySQL with XAMPP)

## 🚀 Installation & Setup

### 🔹 **Step 1: Clone the Repository**

Open your terminal and run:

```bash
git clone https://github.com/kushal961/Hackshastra.git
cd Hackshastra
```

### 🔹 **Step 2: Set Up the Database**

1. Open **XAMPP Control Panel** and start **Apache & MySQL**.
2. Open **phpMyAdmin** (`http://localhost/phpmyadmin/`).
3. Create a new database, e.g., **`hackshastra_db`**.
4. Import the SQL file (`hackshastra.sql`) from the project folder.

### 🔹 **Step 3: Configure the Project**

- Open **`config.php`** and update database credentials:
  ```php
  $host = "localhost";
  $user = "root"; // Change if needed
  $password = "";
  $database = "your database name_db";
  ```
- Save the file.

### 🔹 **Step 4: Run the Project Locally**

1. Move the project folder to `C:\xampp\htdocs\`.
2. Open the browser and go to:
   ```
   http://localhost/Hackshastra/
   ```
3. Register/Login and start using the platform! 🎉

## 📝 Usage Instructions

- **Register/Login** to access the dashboard.
- **Post a Team Request** if you're looking for members.
- **View & Apply** for existing team requests.
- **Delete a Request** once the team is full.
- **Explore Hackathons** and find opportunities.



## 💎 Contact

If you need help, feel free to reach out! 😊

