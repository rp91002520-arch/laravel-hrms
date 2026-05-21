# HRMS Leave & Attendance Module API

## Project Overview

This project is a mini HRMS (Human Resource Management System) module built using Laravel.

It includes:

- Employee Management
- Leave Management
- Leave Approval System
- Leave Balance Management
- Attendance Summary API
- Sandwich Rule Implementation
- Overlapping Leave Validation

---

# Features

## 1. Employee Module

- Add Employee
- Edit Employee
- Delete Employee
- List Employees

### Employee Fields

- Employee Code
- Name
- Email
- Phone
- Department
- Joining Date

---

## 2. Leave Management

Employees can:

- Apply Leave
- View Leave History

### Leave Fields

- Employee
- Leave Type
    - Paid
    - Sick
    - Casual
- From Date
- To Date
- Reason
- Status
    - Pending
    - Approved
    - Rejected

---

## 3. Admin Features

Admin can:

- Approve Leave
- Reject Leave
- Add Remarks
- View Leave Statistics

---

# Business Logic

## A. Overlapping Leave Validation

Employees cannot apply for overlapping leave dates.

---

## B. Sandwich Rule

Implemented scenarios:

- AB - WO - AB → WO counted as leave
- AB - WO - WO - AB → Both WO counted as leave

---

## C. Leave Balance Management

Default Leave Balance:

- Paid Leave → 12
- Sick Leave → 6

Leave balance automatically reduces after approval.

---

# Technical Requirements Implemented

- Laravel Latest Version
- Migration & Seeder
- Eloquent Relationships
- Form Request Validation
- Pagination
- Avoided N+1 Queries
- Proper Code Structure
- Database Transactions

---

# API Endpoint

## Attendance Summary API

```http
GET /api/attendance-summary
```

### Response

```json
{
    "total_employees": 10,
    "present": 7,
    "absent": 1,
    "on_leave": 2,
    "week_off": 0
}
```

---

# Installation Steps

## 1. Clone Repository

```bash
git clone https://github.com/rp91002520-arch/laravel-hrms.git
```

---

## 2. Open Project Folder

```bash
cd laravel-hrms
```

---

## 3. Install Dependencies

```bash
composer install
```

---

## 4. Create Environment File

```bash
copy .env.example .env
```

---

## 5. Generate Application Key

```bash
php artisan key:generate
```

---

## 6. Configure Database

Update `.env` file:

```env
DB_DATABASE=laravel_hrms
DB_USERNAME=root
DB_PASSWORD=
```

---

## 7. Import Database

Import provided SQL file using phpMyAdmin.

---

## 8. Run Migration

```bash
php artisan migrate
```

---

## 9. Run Seeder

```bash
php artisan db:seed
```

---

# Run Project

```bash
php artisan serve
```

Server:

```text
http://127.0.0.1:8000
```

---

# API Testing

Use:

- Postman
- Thunder Client

---

# Technologies Used

- Laravel
- MySQL
- REST API

---

# Author

Pankaj Singh