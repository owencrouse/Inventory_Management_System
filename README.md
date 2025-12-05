# 🧾 Inventory Management System

## Introduction

This project was created to demonstrate the knowledge and skills learned throughout **CP476 (Internet Computing)** and to gain a deeper understanding of internet-based system development by designing and building a web server that enables interaction between web users and a database server.

The project incorporates core concepts such as:
- **Web server-client architecture**
- **Database integration**
- **Secure communication** through server-side scripting

The system is built using **PHP** as the server-side language, running on an **Apache web server** hosted on a **Windows** operating system.

Users can:
- Log in securely  
- Search, update, and delete entries in the **Product** and **Supplier** tables  
- View combined data in the **Inventory** table  

All database interactions are handled through **prepared SQL statements** to prevent **SQL injection** and ensure data security.

The database schema includes:
- `User`
- `Supplier`
- `Product`
- `Inventory`

Each table is linked using **foreign keys** and **SQL joins**, forming a robust relational structure that supports real-time updates and secure user interaction.  
This project demonstrates the complete process — from **database setup** to **user manipulation** of data through a web interface.

---

## System Overview

The **Inventory Management System** is a centralized platform designed for efficient inventory tracking and management through an intuitive and secure web interface.

### Architecture Overview:
- **Frontend:** JavaScript-based interface for interactivity and form handling  
- **Backend:** Apache server executing PHP scripts to process HTTP requests  
- **Database:** MySQL for data persistence, accessed through **prepared statements** to ensure efficiency and protection from SQL injection  

The system allows real-time **search, update, and deletion** of inventory data, ensuring accurate and up-to-date management across all tables.

---

## Major Functionalities

### Login & Authentication

Only authorized users can access the system.

Features:
- Secure credential validation via **email and password**
- **Error handling** for invalid login attempts
- **Cache-based authentication** for session persistence  

When valid credentials are entered, PHP verifies them and creates a cache token, granting the user access to the system dashboard.

---

### Search Functionality

The **Search Functionality** allows users to quickly find inventory, supplier, or product items based on a search query.

**Process:**
1. User enters a keyword into the search bar  
2. The search term is sent via a **GET** request  
3. PHP executes a prepared SQL query  
4. The page reloads with matching search results  

This functionality enables efficient data retrieval by name or keyword.

---

### Update Functionality

Users can edit **Product** or **Supplier** information directly through in-table editing forms.

- **Primary keys (Product ID / Supplier ID)** remain fixed  
- Other fields are editable  
- Updates automatically propagate to the **Inventory** view, since it is a combination of both tables  

**Workflow:**
1. Click **Edit** beside a record  
2. Submit the update via a **POST** form  
3. Receive a success or error message upon completion  

---

### Delete Records

Users can remove specific **Product** or **Supplier** entries with one click.

- Deletions are performed through secure **POST** requests  
- After deletion, the interface refreshes with a confirmation message  

**Dependency Rules:**
- A supplier cannot be deleted if products are still linked to them  
- Attempting this displays an error message to maintain referential integrity  

---

### View Tables

Users can view any of the main tables:
- **Product**
- **Supplier**
- **Inventory**

Each table:
- Loads automatically on page refresh  
- Is sorted by ID  
- Displays action buttons for **Edit** and **Delete**

This ensures all information is current and accessible from one central dashboard.

---

### Logout

Users can securely log out by clicking the **Logout** button.

- This destroys the active cache token  
- Redirects users to the login page  
- Ensures sessions are properly terminated for security  

---

## Technologies Used

| Component | Technology |
|------------|-------------|
| **Frontend** | HTML, CSS, JavaScript |
| **Backend** | PHP |
| **Web Server** | Apache |
| **Database** | MySQL (Prepared Statements) |
| **Operating System** | Windows |

---

## Key Features Summary

- Secure **login and session management**
- Dynamic **search**, **update**, and **delete** operations
- **SQL injection prevention** using prepared statements
- **Relational database** design with foreign keys and joins
- **Responsive web interface** built with standard web technologies

---

## Collaborators

| Name | Role | Contributions |
|------|------|----------------|
| **Owen Crouse** | Database Design | PHP logic, prepared statement implementation, database setup |
| **Charles Rocchi** | Frontend Developer | Tester / Error Handling, HTML/CSS/JS interface design, form validation |
| **Benoit Lariviere** | Frontend Developer | Tester / Documentation, System testing, bug reporting | 


---

## Installation & Setup (Optional)

If you want to include setup instructions, here’s a quick template:

```bash
# Clone the repository
git clone https://github.com/yourusername/inventory-management-system.git

# Move into the project directory
cd inventory-management-system

# Start Apache and MySQL (e.g., using XAMPP or WAMP)
# Place the project folder in the htdocs directory

# Open the app in your browser
http://localhost/inventory-management-system/
