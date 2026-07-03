# RoadHealth AI 🛣️🤖

An AI-powered road and pavement defect detection, classification, and maintenance management platform designed to automate and streamline municipal infrastructure maintenance.

By leveraging Google Gemini's advanced multimodal LLMs, RoadHealth AI converts simple pavement photographs into actionable, geo-tagged maintenance tasks. The platform facilitates collaboration between **Citizens**, **Municipal Officers**, and **Field Maintenance Staff** in a unified workflow.

---

## 🧑‍💻 Author & Developer
This project has been developed entirely and exclusively by:
* **Karthik Nadamritham** ([@karthiknadamritham](https://github.com/karthiknadamritham))

---

## 🌟 Key Features

* **AI Road Surface Verification**: Ensures that only valid images of roads or pavements are accepted for analysis, rejecting random objects, diagrams, or people.
* **Automated Defect Detection & Classification**: Identifies and quantifies road defects including:
  * Potholes
  * Alligator Cracks
  * Longitudinal & Transverse Cracks
  * Minor Cracks
  * Surface Wear
  * Patches
* **PCI (Pavement Condition Index) Calculation**: Automatically estimates the PCI score (from 0 to 100) based on ASTM D6433 standards to determine pavement health.
* **Geographical Tagging**: Integrates coordinates (Latitude and Longitude) to map defects precisely.
* **Role-Based Portals**:
  * **Citizen Portal**: Upload road scans, report issues, and monitor progress of reports.
  * **Officer Portal**: Review AI scans, approve registrations, assign maintenance tasks to field staff, and verify task completion.
  * **Staff Portal**: View assigned maintenance jobs, submit real-time progress updates with photos, and request work verification.
  * **Admin Portal**: System-wide configuration and administrative management.
* **AI Pavement Civil Engineer Chat Assistant**: An interactive chatbot helper with real-time access to live database metrics for querying pavement engineering suggestions.
* **Real-time Notifications**: Keeps all users informed of work order updates, assignment notifications, and registration approvals.

---

## 🛠️ Architecture & Tech Stack

* **Frontend & Web Server**: Laravel 11.x (PHP framework) with Blade Templating, Vanilla/Tailwind CSS, and Vite.
* **Database**: SQLite (default setup for ease of local hosting).
* **AI API Gateway**: Python FastAPI running on port `8001`.
* **AI Engine**: Google GenAI SDK utilizing `gemini-3.5-flash` with robust fallback logic to `gemini-2.5-flash` and `gemini-2.0-flash`.

---

## 🚀 Installation & Setup

### Prerequisites
Make sure you have the following installed on your machine:
* PHP >= 8.2 & Composer
* Node.js & NPM
* Python 3.9+ & pip
* Git

---

### Step 1: Set up the Laravel Web App

1. Clone the repository and navigate to the directory:
   ```bash
   cd ROADHEALTH_AI
   ```

2. Copy the environment template:
   ```bash
   copy .env.example .env
   ```

3. Generate the application key:
   ```bash
   php artisan key:generate
   ```

4. Install PHP dependencies:
   ```bash
   composer install
   ```

5. Install and compile frontend assets:
   ```bash
   npm install
   npm run build # Or use 'npm run dev' to run the development server
   ```

6. Set up the database and seed the default user roles:
   ```bash
   php artisan migrate --seed
   ```

---

### Step 2: Set up the Python AI Service

1. Navigate to the `python-api` folder:
   ```bash
   cd python-api
   ```

2. Create and activate a Python virtual environment:
   ```bash
   # Windows
   python -m venv venv
   .\venv\Scripts\activate

   # macOS/Linux
   python3 -m venv venv
   source venv/bin/activate
   ```

3. Install the dependencies:
   ```bash
   pip install -r requirements.txt
   ```

4. Create/Edit the `.env` file in the root directory (or in the `python-api` directory) and add your **Gemini API Key**:
   ```env
   GEMINI_API_KEY="your-google-gemini-api-key-here"
   ```

---

## 🏃 Running the Application

To run the application locally, you will need to start both the Laravel web server and the FastAPI service.

1. **Start the Laravel Server** (in the root directory):
   ```bash
   php artisan serve
   ```
   *The web application will be accessible at http://127.0.0.1:8000.*

2. **Start the FastAPI Server** (in the `python-api` directory, with virtual environment activated):
   ```bash
   python main.py
   ```
   *The AI gateway will start running on http://127.0.0.1:8001.*


