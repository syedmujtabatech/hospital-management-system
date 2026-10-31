# 🏥 Al-Shifa International Hospital Management System (HMS)

An enterprise-grade, comprehensive **Hospital Management System (HMS)** built with **Laravel 12**, **MySQL**, and the **Sneat Bootstrap 5 Admin Panel** (with Boxicons, FontAwesome, and ApexCharts). Designed following extensive research of tier-1 clinical hospital operations (OpenEMR, Epic, Cerner, Practo).

---

## 🚀 Quick Start (1-Click Run)

Simply double click the **`run_project.bat`** file located inside `D:\hospital-management-system`, or run in terminal:

```bash
cd D:\hospital-management-system
php artisan serve
```

Then open your browser at: **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## 🔑 Demo Login Accounts

You can log in manually using the credentials below, or click any of the **1-Click Quick Demo Login buttons** on the login page:

| Role | Email | Password | Access Scope |
| :--- | :--- | :--- | :--- |
| **Hospital Administrator** | `admin@hospital.com` | `admin123` | Full Access across all 10 Modules |
| **Consultant Physician** | `doctor@hospital.com` | `doctor123` | OPD Checkup, Prescriptions, Clinical History |
| **Front Desk Reception** | `reception@hospital.com` | `reception123` | Patient Registration, Appointments, IPD Beds |
| **Head Pharmacist** | `pharmacy@hospital.com` | `pharmacy123` | Medicine Inventory, Stock Alerts, Drug Dispensing |
| **Chief Lab Technician** | `lab@hospital.com` | `lab123` | Pathology Orders, Result Entry, Certified Reports |

---

## 🌟 Core Modules & Clinical Capabilities

### 1. 📋 Patient Electronic Medical Records (360° EMR)
- **Automatic MRN Generation**: Auto-generates unique Medical Record Numbers (`MRN-2026-XXXX`).
- **Demographics & Vitals**: Age, DOB, Gender, Blood group, Contact & Emergency Contact.
- **Critical Clinical Alerts**: Prominently highlights Drug Allergies and Chronic Illnesses.
- **360° EMR View**: Single-screen view of patient's lifetime consultations, prescriptions, lab reports, inpatient ward history, and bills.

### 2. 👨‍⚕️ Specialist Doctors & Departments
- **Multi-Department Directory**: Cardiology, Neurology, Orthopedics, Pediatrics, General Medicine, Emergency.
- **Doctor Profiles**: Specialization, Qualification, PMDC License #, OPD Clinic Room, Consultation Fees.
- **Auto User Provisioning**: Creating a doctor automatically provisions their staff portal credentials.

### 3. 📅 Outpatient Appointments Queue
- **Schedule Management**: Daily patient queue with time slots and consultation dates.
- **Priority Triage**: Normal, Urgent, and STAT/Emergency triage flags.
- **Direct Checkup Action**: 1-click button to launch doctor's clinical checkup workstation.

### 4. 🩺 OPD Clinical Examination & Digital Prescriptions (Rx)
- **Vitals Matrix**: Blood Pressure (mmHg), Pulse Rate (bpm), Body Temperature (°F), Oxygen SpO2 (%), Respiratory Rate (/min), Weight (kg).
- **Clinical Evaluation**: Symptoms, Chief Complaints, Clinical Diagnosis, Lifestyle notes.
- **Dynamic Prescription Builder**: Select medicines directly from pharmacy inventory with real-time stock verification, dosage, timing (1-0-1), and duration.
- **Printable Rx Slip**: Official, beautiful doctor prescription slip with hospital logo, doctor PMDC license, and signature line.

### 5. 🛏️ Inpatient (IPD) Admissions & Interactive Bed Matrix
- **Visual Bed Matrix**: Interactive color-coded hospital layout (Green = Available, Red = Occupied, Yellow = Maintenance).
- **Wards Covered**: General Male Ward, General Female Ward, Semi-Private, Deluxe Suite, and ICU.
- **1-Click Bed Allotment**: Click any available bed to admit a patient.
- **Discharge Summary**: Auto-releases bed back to available, records discharge condition, and saves post-discharge notes.

### 6. 💊 Pharmacy Inventory & Low-Stock Alerts
- **Batch Tracking**: Brand name, Generic formula, Category, Dosage form, Batch #, Expiry date.
- **Pricing & Margin**: Unit cost price vs. retail selling price.
- **Automated Stock Alerts**: Real-time alerts when stock drops below threshold quantity.
- **Quick Stock Replenishment**: Modal to rapidly add stock units.

### 7. 🧪 Laboratory & Diagnostics (Pathology/Radiology)
- **Standardized Test Catalog**: CBC, Lipid Profile, LFT, Chest X-Ray, etc., with normal reference intervals.
- **Requisition Management**: Order tests for outpatients or admitted inpatients.
- **Result Validation**: Pathologist result entry with qualitative/quantitative parameters.
- **Official Printable Diagnostic Report**: Certified diagnostic report with ISO reference lab branding.

### 8. 🧾 Consolidated Billing, Invoicing & Receipts
- **Itemized Billing**: Dynamic row generation for Consultation, Ward Bed charges, Pharmacy, Lab, and Nursing.
- **Tax & Discount Engine**: Automatic calculation of subtotals, VAT/Tax, discounts, and net payable.
- **Multi-Mode POS Receipts**: Supports Cash, POS Card, Bank Transfer, and Online payments.
- **Printable Tax Invoice**: Official hospital invoice with patient MRN, itemized charges, and payment receipt breakdown.

---

## 🛠️ Technology Stack
- **Backend**: Laravel 12 (PHP 8.2+)
- **Database**: MySQL (`hospital_management_system`)
- **Admin UI Theme**: Sneat Bootstrap 5 Admin Panel
- **Icons**: Boxicons, FontAwesome 6, Iconify
- **Charts**: ApexCharts
