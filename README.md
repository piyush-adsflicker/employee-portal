# Employee Management & Smart Attendance System (EMS)

A comprehensive, multi-guard Employee Management and HRMS platform built with **Laravel 11, Tailwind CSS, and FullCalendar**. 

This system provides a clean separation between the **Admin Workspace** (HR/Management) and the **Employee StaffPortal**. It features a highly advanced, intelligent attendance engine capable of tracking precise GPS locations, reverse geocoding addresses, tracking breaks, and managing full employee 360° lifecycles.

---

## ✨ Core Features

### 1) Smart Attendance Engine
* **GPS & Area Capture:** Automatically fetches coordinates and uses BigDataCloud/OpenStreetMap to reverse-geocode the exact area/street name on Check-In and Check-Out.
* **Device & IP Logging:** Silently logs the employee's IP address and User Agent (Device/Browser).
* **Live Timer & Break Tracking:** Employees see a live elapsed timer. Supports "Start/End Break" functionality with automatic net-hour calculations.
* **Half-Day & Late Math:** Shifts dictate grace periods, total working hours, and half-day thresholds. The system automatically tags records as `Present`, `Late`, `Half Day`, or `Absent`.

### 2) Employee 360° CRM
* **Unified Profile:** Track Employment Type, CTC/Salary, Skills, Previous Experience, and Emergency Contacts.
* **Career Timeline:** Chronological feed tracking joining dates, appraisals, promotions, disciplinary warnings, and approved leaves.
* **Document Vault:** Securely upload, view, and manage HR documents (Offer Letters, NDAs, ID Proofs).
* **Asset Management:** Assign hardware (Laptops, Mobiles, ID cards) directly from the inventory to the employee.

### 3) Leave & Holiday Management
* **Smart Leave Application:** Automatically excludes non-working weekends (Sundays) and official active holidays when calculating total requested leave days.
* **Balance Tracking:** Custom leave types (Sick, Casual, Paid) with default annual allowances and strict balance deduction upon Admin approval.
* **Global Holidays:** Interactive holiday calendar configured by the Admin.

### 4) Master Setup & Organization
* **Departments & Designations:** Build your organizational hierarchy.
* **Shift Configuration:** Support for multiple shifts with custom start/end times, half-day hour limits, and grace periods.
* **Dual Dashboard System:** Context-aware portal routing users to their respective teal-themed (Employee) or indigo-themed (Admin) workspaces.

---

## 🛠️ Tech Stack

* **Backend:** Laravel 11.x, PHP 8.2+
* **Frontend:** Blade Templating, Tailwind CSS (via Vite)
* **Database:** MySQL / SQLite
* **Interactivity:** SweetAlert2 (Modals/Alerts), FullCalendar v6.1.10 (Attendance/Holiday mapping)
* **Geolocation:** HTML5 Navigator API + BigDataCloud Reverse Geocoding API

---

## 🚀 Installation & Setup Guide

Follow these steps to set up the project locally on your machine.

### Prerequisites
* PHP 8.2 or higher
* Composer
* Node.js & NPM
* MySQL or SQLite

### 1. Clone the Repository
```bash
git clone [https://github.com/yourusername/employee-management-system.git](https://github.com/yourusername/employee-management-system.git)
cd employee-management-system