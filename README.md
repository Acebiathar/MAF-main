# MEDICINE AVAILABILITY FINDER

## Problem & Solution
### The Problem
Many patients in Uganda spend a significant amount of time moving from one pharmacy to another searching for prescribed medicines.
This leads to delays in treatment, increased transport costs, and frustration. 
At the same time, pharmacies struggle to reach customers despite having medicines in stock.

---

### The Solution
The Medicine Availability Finder System (MAF) is a web-based platform that connects patients with registered pharmacies. 
Patients can search for medicines, compare prices, check availability, and locate nearby pharmacies. 
Pharmacists can update their medicine stock and prices in real time, while administrators manage pharmacy registrations and monitor the system.

---

### Key Features
- User registration and login
- Search medicines by name
- View medicine availability across pharmacies
- Compare medicine prices
- Reserve medicines online
- Pharmacist dashboard for stock management
- Admin dashboard for user and pharmacy management
- Real-time medicine availability updates

---

## Technologies Used
- *Backend:* Laravel
- *Frontend:* Blade Templates, HTML, CSS, JavaScript, Bootstrap
- *Database:* PostgreSQL
- *Version Control:* Git & GitHub

---

## Setup Instructions
Follow these steps to install, configure, and run the project locally.

---

### Prerequisites
Ensure the following software is installed:

- PHP 8.2 or later
- Composer
- PostgreSQL
- Git
- Node.js and npm

---

### Installation & Configuration
Provide a step-by-step guide to get the environment ready. Use code blocks for terminal commands.

---

### Step 1: Clone the Repository
git clone https://github.com/your-username/medicine-availability-finder.git

Move into the project folder:

bash
cd medicine-availability-finder

---

### Step 2: Install PHP Dependencies

bash
composer install

---

### Step 3: Install JavaScript Dependencies

bash
npm install

---

### Step 4: Configure Environment

Copy the environment file:

bash
cp .env.example .env


Edit the .env file and update the PostgreSQL database details.

Example:

env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=medicine_finder
DB_USERNAME=postgres
DB_PASSWORD=your_password


---

### Step 5: Generate Application Key

bash
php artisan key:generate


---

### Step 6: Run Database Migrations

bash
php artisan migrate


If your project contains seeders:

bash
php artisan db:seed


---

### Step 7: Build Frontend Assets

bash
npm run dev


---

### Step 8: Start the Development Server

bash
php artisan serve


Open your browser and visit:


http://127.0.0.1:8000


---

## Project Structure


app/
bootstrap/
config/
database/
public/
resources/
routes/
storage/
tests/


---

## Authors

ABIATHAR SANDE  >>>  2024/DCS/DAY/1046/G
NABUKENYA STELLAH  >>>  2024/DCS/DAY/0305
NADDAMBA LAMULAH  >>>  2024/DCS/DAY/0963/G
TUSINGWIRE RUTH  >>>  2024/DCS/DAY/0903
TUSINGWIRE PATRICIA  >>>  2024/DCS/DAY/1875

Uganda Institute of Information and Communications Technology (UICT)

Diploma in Computer Science

---

## License

This project was developed for academic purposes as a final-year project at the Uganda Institute of Information and Communications Technology (UICT).
