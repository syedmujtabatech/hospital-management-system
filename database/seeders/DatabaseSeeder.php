<?php

namespace Database\Seeders;

use App\Models\Admission;
use App\Models\Appointment;
use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Encounter;
use App\Models\LabOrder;
use App\Models\LabTest;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Staff / Users with Roles
        $admin = User::create([
            'name'     => 'Dr. Robert Mitchell',
            'email'    => 'admin@hospital.com',
            'phone'    => '+1 (555) 019-2831',
            'role'     => 'admin',
            'password' => Hash::make('admin123'),
            'status'   => true,
        ]);

        $doctorUser = User::create([
            'name'     => 'Dr. Sarah Jenkins',
            'email'    => 'doctor@hospital.com',
            'phone'    => '+1 (555) 019-2832',
            'role'     => 'doctor',
            'password' => Hash::make('doctor123'),
            'status'   => true,
        ]);

        User::create([
            'name'     => 'Emily Watson',
            'email'    => 'reception@hospital.com',
            'phone'    => '+1 (555) 019-2833',
            'role'     => 'receptionist',
            'password' => Hash::make('reception123'),
            'status'   => true,
        ]);

        User::create([
            'name'     => 'Michael Chang',
            'email'    => 'pharmacy@hospital.com',
            'phone'    => '+1 (555) 019-2834',
            'role'     => 'pharmacist',
            'password' => Hash::make('pharmacy123'),
            'status'   => true,
        ]);

        User::create([
            'name'     => 'Amina Khan',
            'email'    => 'lab@hospital.com',
            'phone'    => '+1 (555) 019-2835',
            'role'     => 'lab_tech',
            'password' => Hash::make('lab123'),
            'status'   => true,
        ]);

        // 2. Departments
        $cardio = Department::create([
            'name'        => 'Cardiology & Heart Care',
            'code'        => 'CARD',
            'description' => 'Comprehensive cardiac care, angiography, ECG, echocardiography, and heart failure management.',
            'icon'        => 'bx-heart',
            'status'      => true,
        ]);

        $neuro = Department::create([
            'name'        => 'Neurology & Brain Sciences',
            'code'        => 'NEUR',
            'description' => 'Advanced diagnostics and treatment for stroke, epilepsy, neuromuscular disorders, and spine health.',
            'icon'        => 'bx-brain',
            'status'      => true,
        ]);

        $ortho = Department::create([
            'name'        => 'Orthopedics & Joint Surgery',
            'code'        => 'ORTHO',
            'description' => 'Bone fracture trauma, joint replacement, sports injury rehab, and spine deformity correction.',
            'icon'        => 'bx-bone',
            'status'      => true,
        ]);

        $peds = Department::create([
            'name'        => 'Pediatrics & Child Health',
            'code'        => 'PED',
            'description' => 'Neonatal intensive care (NICU), child immunizations, development screening, and acute pediatric care.',
            'icon'        => 'bx-child',
            'status'      => true,
        ]);

        $genMed = Department::create([
            'name'        => 'General & Internal Medicine',
            'code'        => 'GEN',
            'description' => 'Primary outpatient consultations, chronic diabetes, hypertension, and fever investigation clinics.',
            'icon'        => 'bx-plus-medical',
            'status'      => true,
        ]);

        $emer = Department::create([
            'name'        => 'Emergency & Trauma Care (24/7)',
            'code'        => 'EMER',
            'description' => 'Immediate critical resuscitation, acute poisoning, road trauma, and acute myocardial infarction response.',
            'icon'        => 'bx-first-aid',
            'status'      => true,
        ]);

        // 3. Doctors
        $doc1 = Doctor::create([
            'user_id'          => $doctorUser->id,
            'department_id'    => $cardio->id,
            'name'             => 'Dr. Sarah Jenkins',
            'email'            => 'doctor@hospital.com',
            'phone'            => '+1 (555) 019-2832',
            'specialization'   => 'Interventional Cardiology',
            'qualification'    => 'MD (Cardiology), MBBS, FACC (USA)',
            'consultation_fee' => 75.00,
            'schedule_days'    => 'Mon, Tue, Wed, Thu, Fri',
            'schedule_hours'   => '09:00 AM - 02:00 PM',
            'room_number'      => 'OPD-102 (First Floor)',
            'bio'              => 'Senior Consultant Cardiologist with 14+ years of clinical experience in coronary angiograms, angioplasty, and cardiac rehabilitation.',
            'status'           => true,
        ]);

        $doc2 = Doctor::create([
            'department_id'    => $neuro->id,
            'name'             => 'Dr. Ahmed Al-Mansoor',
            'email'            => 'mansoor@hospital.com',
            'phone'            => '+1 (555) 019-3344',
            'specialization'   => 'Neuro-Physician',
            'qualification'    => 'MBBS, FCPS (Neurology), MRCP (UK)',
            'consultation_fee' => 80.00,
            'schedule_days'    => 'Mon, Wed, Fri',
            'schedule_hours'   => '11:00 AM - 04:00 PM',
            'room_number'      => 'OPD-205 (Second Floor)',
            'bio'              => 'Specialist in stroke intervention, chronic migraines, parkinsons disease, and electroencephalography (EEG).',
            'status'           => true,
        ]);

        $doc3 = Doctor::create([
            'department_id'    => $ortho->id,
            'name'             => 'Dr. Marcus Vance',
            'email'            => 'vance@hospital.com',
            'phone'            => '+1 (555) 019-4455',
            'specialization'   => 'Orthopedic & Spine Surgeon',
            'qualification'    => 'MS (Ortho), FRCS, Fellowship in Arthroplasty',
            'consultation_fee' => 70.00,
            'schedule_days'    => 'Tue, Thu, Sat',
            'schedule_hours'   => '10:00 AM - 03:00 PM',
            'room_number'      => 'OPD-110 (First Floor)',
            'bio'              => 'Renowned orthopedic surgeon specializing in knee and hip arthroplasty, complex trauma management, and sports arthroscopy.',
            'status'           => true,
        ]);

        $doc4 = Doctor::create([
            'department_id'    => $peds->id,
            'name'             => 'Dr. Elena Rostova',
            'email'            => 'elena@hospital.com',
            'phone'            => '+1 (555) 019-5566',
            'specialization'   => 'Pediatric Care & Neonatology',
            'qualification'    => 'MD (Pediatrics), DCH, Fellowship in Neonatal ICU',
            'consultation_fee' => 60.00,
            'schedule_days'    => 'Mon, Tue, Wed, Thu, Fri, Sat',
            'schedule_hours'   => '08:30 AM - 01:30 PM',
            'room_number'      => 'OPD-104 (First Floor)',
            'bio'              => 'Compassionate pediatrician focusing on preventive child health, developmental milestones, and acute respiratory illnesses.',
            'status'           => true,
        ]);

        $doc5 = Doctor::create([
            'department_id'    => $genMed->id,
            'name'             => 'Dr. Farhan Siddiqui',
            'email'            => 'siddiqui@hospital.com',
            'phone'            => '+1 (555) 019-6677',
            'specialization'   => 'Internal Medicine Specialist',
            'qualification'    => 'MBBS, MCPS, MD (Internal Medicine)',
            'consultation_fee' => 50.00,
            'schedule_days'    => 'Mon, Tue, Wed, Thu, Fri, Sat',
            'schedule_hours'   => '04:00 PM - 09:00 PM',
            'room_number'      => 'OPD-101 (Ground Floor)',
            'bio'              => 'Expert clinician managing complicated diabetes mellitus, dyslipidemia, thyroid abnormalities, and viral fevers.',
            'status'           => true,
        ]);

        // 4. Patients
        $pat1 = Patient::create([
            'mrn'                     => 'MRN-2026-0001',
            'first_name'              => 'William',
            'last_name'               => 'Harrison',
            'date_of_birth'           => '1979-04-12',
            'age'                     => 47,
            'gender'                  => 'Male',
            'blood_group'             => 'O+',
            'phone'                   => '+1 (555) 432-8871',
            'email'                   => 'william.harrison@gmail.com',
            'address'                 => '742 Evergreen Terrace, Springfield',
            'emergency_contact_name'  => 'Mary Harrison (Wife)',
            'emergency_contact_phone' => '+1 (555) 432-8872',
            'allergies'               => 'Penicillin (Severe rash)',
            'chronic_conditions'      => 'Essential Hypertension (Stage 2), Mild Hyperlipidemia',
            'notes'                   => 'Regular cardiac checkup patient. Monitored on ACE inhibitor.',
        ]);

        $pat2 = Patient::create([
            'mrn'                     => 'MRN-2026-0002',
            'first_name'              => 'Sophia',
            'last_name'               => 'Martinez',
            'date_of_birth'           => '1992-09-24',
            'age'                     => 34,
            'gender'                  => 'Female',
            'blood_group'             => 'B+',
            'phone'                   => '+1 (555) 871-3329',
            'email'                   => 'sophia.m@outlook.com',
            'address'                 => '1204 Pine Creek Road, Seattle, WA',
            'emergency_contact_name'  => 'Carlos Martinez (Brother)',
            'emergency_contact_phone' => '+1 (555) 871-3330',
            'allergies'               => 'Sulfa Drugs',
            'chronic_conditions'      => 'Bronchial Asthma (Triggered by dust)',
            'notes'                   => 'Carries rescue inhaler. Under routine pulmonology observation.',
        ]);

        $pat3 = Patient::create([
            'mrn'                     => 'MRN-2026-0003',
            'first_name'              => 'Arthur',
            'last_name'               => 'Pendelton',
            'date_of_birth'           => '1961-11-05',
            'age'                     => 65,
            'gender'                  => 'Male',
            'blood_group'             => 'A+',
            'phone'                   => '+1 (555) 234-9988',
            'email'                   => 'arthur.p@yahoo.com',
            'address'                 => '88 Elm Street, Brooklyn, NY',
            'emergency_contact_name'  => 'Grace Pendelton (Daughter)',
            'emergency_contact_phone' => '+1 (555) 234-9989',
            'allergies'               => 'None known',
            'chronic_conditions'      => 'Type 2 Diabetes Mellitus, Osteoarthritis of Right Knee',
            'notes'                   => 'Admitted for right total knee arthroplasty scheduled this week.',
        ]);

        $pat4 = Patient::create([
            'mrn'                     => 'MRN-2026-0004',
            'first_name'              => 'Zainab',
            'last_name'               => 'Tariq',
            'date_of_birth'           => '2019-06-18',
            'age'                     => 7,
            'gender'                  => 'Female',
            'blood_group'             => 'AB+',
            'phone'                   => '+1 (555) 901-4455',
            'email'                   => 'tariq.family@gmail.com',
            'address'                 => '45 Meadow Lane, Austin, TX',
            'emergency_contact_name'  => 'Tariq Mahmood (Father)',
            'emergency_contact_phone' => '+1 (555) 901-4456',
            'allergies'               => 'Peanut allergy',
            'chronic_conditions'      => 'None',
            'notes'                   => 'Pediatric wellness visit and booster immunization check.',
        ]);

        $pat5 = Patient::create([
            'mrn'                     => 'MRN-2026-0005',
            'first_name'              => 'David',
            'last_name'               => 'Kim',
            'date_of_birth'           => '1985-02-14',
            'age'                     => 41,
            'gender'                  => 'Male',
            'blood_group'             => 'O-',
            'phone'                   => '+1 (555) 672-1144',
            'email'                   => 'david.kim@techcorp.io',
            'address'                 => '500 Market Street, San Francisco, CA',
            'emergency_contact_name'  => 'Hannah Kim (Spouse)',
            'emergency_contact_phone' => '+1 (555) 672-1145',
            'allergies'               => 'Aspirin, NSAIDs',
            'chronic_conditions'      => 'Chronic Migraines',
            'notes'                   => 'Under neurology investigation for hemiplegic headache spells.',
        ]);

        // 5. Rooms & Beds (IPD)
        $r1 = Room::create([
            'room_number'  => 'GW-101',
            'ward_name'    => 'General Ward (Male)',
            'room_type'    => 'General Ward',
            'daily_charge' => 60.00,
            'capacity'     => 6,
            'status'       => 'Occupied',
        ]);

        $r2 = Room::create([
            'room_number'  => 'GW-102',
            'ward_name'    => 'General Ward (Female)',
            'room_type'    => 'General Ward',
            'daily_charge' => 60.00,
            'capacity'     => 6,
            'status'       => 'Available',
        ]);

        $r3 = Room::create([
            'room_number'  => 'SP-201',
            'ward_name'    => 'Semi-Private Wing (Level 2)',
            'room_type'    => 'Semi-Private',
            'daily_charge' => 120.00,
            'capacity'     => 2,
            'status'       => 'Available',
        ]);

        $r4 = Room::create([
            'room_number'  => 'DP-301',
            'ward_name'    => 'Executive Deluxe Suite',
            'room_type'    => 'Private Deluxe',
            'daily_charge' => 250.00,
            'capacity'     => 1,
            'status'       => 'Occupied',
        ]);

        $r5 = Room::create([
            'room_number'  => 'ICU-01',
            'ward_name'    => 'Intensive Care Unit (Critical)',
            'room_type'    => 'ICU',
            'daily_charge' => 500.00,
            'capacity'     => 1,
            'status'       => 'Available',
        ]);

        $r6 = Room::create([
            'room_number'  => 'ER-01',
            'ward_name'    => 'Emergency Triage & Trauma',
            'room_type'    => 'Emergency',
            'daily_charge' => 150.00,
            'capacity'     => 4,
            'status'       => 'Available',
        ]);

        // 6. Appointments
        $apt1 = Appointment::create([
            'appointment_number'  => 'APT-2026-001',
            'patient_id'          => $pat1->id,
            'doctor_id'           => $doc1->id,
            'department_id'       => $cardio->id,
            'appointment_date'    => now()->format('Y-m-d'),
            'time_slot'           => '09:30 AM',
            'priority'            => 'Normal',
            'problem_description' => 'Follow-up consultation for blood pressure regulation and 12-lead ECG review.',
            'status'              => 'Completed',
        ]);

        $apt2 = Appointment::create([
            'appointment_number'  => 'APT-2026-002',
            'patient_id'          => $pat2->id,
            'doctor_id'           => $doc5->id,
            'department_id'       => $genMed->id,
            'appointment_date'    => now()->format('Y-m-d'),
            'time_slot'           => '10:45 AM',
            'priority'            => 'Urgent',
            'problem_description' => 'Acute chest tightness, dry cough, and wheezing since yesterday night.',
            'status'              => 'Confirmed',
        ]);

        $apt3 = Appointment::create([
            'appointment_number'  => 'APT-2026-003',
            'patient_id'          => $pat5->id,
            'doctor_id'           => $doc2->id,
            'department_id'       => $neuro->id,
            'appointment_date'    => now()->addDay()->format('Y-m-d'),
            'time_slot'           => '11:15 AM',
            'priority'            => 'Normal',
            'problem_description' => 'Recurring unilateral throbbing headache spells accompanied by photophobia.',
            'status'              => 'Scheduled',
        ]);

        $apt4 = Appointment::create([
            'appointment_number'  => 'APT-2026-004',
            'patient_id'          => $pat4->id,
            'doctor_id'           => $doc4->id,
            'department_id'       => $peds->id,
            'appointment_date'    => now()->addDays(2)->format('Y-m-d'),
            'time_slot'           => '09:00 AM',
            'priority'            => 'Normal',
            'problem_description' => 'Annual pediatric growth examination and nutritional assessment.',
            'status'              => 'Scheduled',
        ]);

        // 7. Clinical Encounters (OPD Visits)
        $enc1 = Encounter::create([
            'encounter_number' => 'OPD-2026-001',
            'patient_id'       => $pat1->id,
            'doctor_id'        => $doc1->id,
            'appointment_id'   => $apt1->id,
            'encounter_type'   => 'OPD',
            'visit_date'       => now()->subHours(2),
            'chief_complaint'  => 'Occasional mild morning dizziness and exertional fatigue.',
            'symptoms'         => 'No chest pain, no palpitations, no pedal edema.',
            'bp'               => '138/86 mmHg',
            'pulse'            => '72 bpm',
            'temperature'      => '98.4 F',
            'spo2'             => '99 %',
            'weight'           => '81 kg',
            'diagnosis'        => 'Well-controlled Primary Hypertension on current pharmacotherapy. Mild sinus bradycardia.',
            'doctor_advice'    => 'Continue low-sodium DASH diet. Daily 30 mins brisk walking. Re-check BP profile in 4 weeks.',
            'status'           => 'Completed',
        ]);

        // 8. Medicines (Pharmacy Catalog)
        $med1 = Medicine::create([
            'name'           => 'Amlodipine 5mg',
            'generic_name'   => 'Amlodipine Besylate',
            'category'       => 'Tablet',
            'batch_number'   => 'BCH-AML-991',
            'purchase_price' => 2.50,
            'sale_price'     => 6.00,
            'stock_quantity' => 450,
            'alert_quantity' => 50,
            'expiry_date'    => '2028-06-30',
            'manufacturer'   => 'Pfizer Pharmaceuticals',
            'status'         => true,
        ]);

        $med2 = Medicine::create([
            'name'           => 'Atorvastatin 20mg',
            'generic_name'   => 'Atorvastatin Calcium',
            'category'       => 'Tablet',
            'batch_number'   => 'BCH-ATV-824',
            'purchase_price' => 4.00,
            'sale_price'     => 9.50,
            'stock_quantity' => 280,
            'alert_quantity' => 40,
            'expiry_date'    => '2028-01-15',
            'manufacturer'   => 'Novartis',
            'status'         => true,
        ]);

        $med3 = Medicine::create([
            'name'           => 'Salbutamol Inhaler (100 mcg)',
            'generic_name'   => 'Albuterol Sulfate',
            'category'       => 'Drops',
            'batch_number'   => 'BCH-SLB-103',
            'purchase_price' => 8.00,
            'sale_price'     => 16.00,
            'stock_quantity' => 85,
            'alert_quantity' => 20,
            'expiry_date'    => '2027-11-20',
            'manufacturer'   => 'GlaxoSmithKline (GSK)',
            'status'         => true,
        ]);

        $med4 = Medicine::create([
            'name'           => 'Augmentin 625mg',
            'generic_name'   => 'Amoxicillin + Clavulanic Acid',
            'category'       => 'Tablet',
            'batch_number'   => 'BCH-AUG-771',
            'purchase_price' => 6.50,
            'sale_price'     => 14.00,
            'stock_quantity' => 14, // Low stock trigger
            'alert_quantity' => 25,
            'expiry_date'    => '2027-09-01',
            'manufacturer'   => 'GSK',
            'status'         => true,
        ]);

        $med5 = Medicine::create([
            'name'           => 'Paracetamol 500mg (Panadol)',
            'generic_name'   => 'Acetaminophen',
            'category'       => 'Tablet',
            'batch_number'   => 'BCH-PCM-002',
            'purchase_price' => 0.80,
            'sale_price'     => 2.50,
            'stock_quantity' => 950,
            'alert_quantity' => 100,
            'expiry_date'    => '2029-03-31',
            'manufacturer'   => 'Sanofi',
            'status'         => true,
        ]);

        $med6 = Medicine::create([
            'name'           => 'Ceftriaxone 1g Injection',
            'generic_name'   => 'Ceftriaxone Sodium IV',
            'category'       => 'Injection',
            'batch_number'   => 'BCH-CFT-440',
            'purchase_price' => 5.00,
            'sale_price'     => 12.00,
            'stock_quantity' => 120,
            'alert_quantity' => 30,
            'expiry_date'    => '2027-04-10',
            'manufacturer'   => 'Roche Diagnostics',
            'status'         => true,
        ]);

        // 9. Prescriptions
        $rx1 = Prescription::create([
            'prescription_number' => 'RX-2026-001',
            'encounter_id'        => $enc1->id,
            'patient_id'          => $pat1->id,
            'doctor_id'           => $doc1->id,
            'prescription_date'   => now()->format('Y-m-d'),
            'notes'               => 'Advised strict medication adherence. Report immediately if ankle swelling occurs.',
        ]);

        PrescriptionItem::create([
            'prescription_id' => $rx1->id,
            'medicine_id'     => $med1->id,
            'medicine_name'   => 'Amlodipine 5mg',
            'dosage'          => '5 mg',
            'frequency'       => 'Once Daily (Morning)',
            'duration'        => '30 Days',
            'instructions'    => 'Take with water after breakfast',
        ]);

        PrescriptionItem::create([
            'prescription_id' => $rx1->id,
            'medicine_id'     => $med2->id,
            'medicine_name'   => 'Atorvastatin 20mg',
            'dosage'          => '20 mg',
            'frequency'       => 'Once Daily (Bedtime)',
            'duration'        => '30 Days',
            'instructions'    => 'Take at night after dinner',
        ]);

        // 10. IPD Admissions
        Admission::create([
            'ipd_number'             => 'IPD-2026-001',
            'patient_id'             => $pat3->id,
            'doctor_id'              => $doc3->id,
            'room_id'                => $r4->id, // DP-301 Deluxe
            'admission_date'         => now()->subDays(2),
            'discharge_date'         => null,
            'admission_reason'       => 'Scheduled elective right knee joint total arthroplasty (TKR). Pre-op cardiac clearance confirmed.',
            'discharge_summary'      => null,
            'condition_on_discharge' => null,
            'initial_deposit'        => 1500.00,
            'status'                 => 'Admitted',
        ]);

        // 11. Laboratory Tests & Orders
        $test1 = LabTest::create([
            'test_name'     => 'Complete Blood Count (CBC with ESR)',
            'test_code'     => 'LAB-CBC',
            'category'      => 'Hematology',
            'standard_cost' => 25.00,
            'normal_range'  => 'WBC: 4.5-11.0 k/uL, Hb: 13.5-17.5 g/dL, Platelets: 150-450 k/uL',
            'unit'          => 'Multi-parameter',
            'description'   => 'Quantitative evaluation of white blood cells, red blood cells, hemoglobin, and platelets.',
            'status'        => true,
        ]);

        $test2 = LabTest::create([
            'test_name'     => 'Comprehensive Lipid Profile',
            'test_code'     => 'LAB-LIPID',
            'category'      => 'Biochemistry',
            'standard_cost' => 40.00,
            'normal_range'  => 'Total Cholesterol < 200 mg/dL, LDL < 100 mg/dL, HDL > 40 mg/dL',
            'unit'          => 'mg/dL',
            'description'   => 'Serum analysis for cardiac risk stratification including total cholesterol, triglycerides, and fractions.',
            'status'        => true,
        ]);

        $test3 = LabTest::create([
            'test_name'     => 'Digital Chest X-Ray (PA View)',
            'test_code'     => 'RAD-CXR',
            'category'      => 'Radiology',
            'standard_cost' => 45.00,
            'normal_range'  => 'Clear lung fields, normal cardiothoracic ratio, intact bony cage',
            'unit'          => 'Visual Imaging',
            'description'   => 'Radiological imaging for evaluation of pulmonary parenchyma, cardiac silhouette, and mediastinum.',
            'status'        => true,
        ]);

        $test4 = LabTest::create([
            'test_name'     => 'Fasting Blood Sugar (FBS) & HbA1c',
            'test_code'     => 'LAB-HBA1C',
            'category'      => 'Biochemistry',
            'standard_cost' => 35.00,
            'normal_range'  => 'FBS: 70-99 mg/dL, HbA1c: 4.0-5.6 % (Non-diabetic)',
            'unit'          => '% / mg/dL',
            'description'   => 'Glycated hemoglobin diagnostic test measuring 3-month glycemic control.',
            'status'        => true,
        ]);

        // Lab Orders
        LabOrder::create([
            'order_number'        => 'LAB-2026-001',
            'patient_id'          => $pat1->id,
            'doctor_id'           => $doc1->id,
            'lab_test_id'         => $test2->id,
            'order_date'          => now()->subDays(1)->format('Y-m-d'),
            'sample_collected_at' => now()->subDays(1)->setTime(10, 0),
            'result_date'         => now()->subHours(5),
            'result_values'       => "Total Cholesterol: 184 mg/dL\nTriglycerides: 142 mg/dL\nHDL Cholesterol: 48 mg/dL\nLDL Cholesterol: 108 mg/dL",
            'technician_remarks'  => 'Serum sample clear, no hemolysis. Lipid control within target range.',
            'status'              => 'Completed',
        ]);

        LabOrder::create([
            'order_number'        => 'LAB-2026-002',
            'patient_id'          => $pat3->id,
            'doctor_id'           => $doc3->id,
            'lab_test_id'         => $test1->id,
            'order_date'          => now()->format('Y-m-d'),
            'sample_collected_at' => now()->setTime(8, 30),
            'result_date'         => null,
            'result_values'       => null,
            'technician_remarks'  => 'Sample received in laboratory. Hematology analyzer processing underway.',
            'status'              => 'Sample Collected',
        ]);

        // 12. Bills & Payments
        $bill1 = Bill::create([
            'invoice_number' => 'INV-2026-001',
            'patient_id'     => $pat1->id,
            'encounter_id'   => $enc1->id,
            'admission_id'   => null,
            'bill_date'      => now()->format('Y-m-d'),
            'due_date'       => now()->format('Y-m-d'),
            'total_amount'   => 130.50,
            'discount'       => 10.00,
            'tax_percentage' => 5.00,
            'net_amount'     => 126.53,
            'paid_amount'    => 126.53,
            'due_amount'     => 0.00,
            'payment_status' => 'Paid',
        ]);

        BillItem::create([
            'bill_id'     => $bill1->id,
            'item_type'   => 'Consultation',
            'item_name'   => 'Cardiology OPD Consultation (Dr. Sarah Jenkins)',
            'quantity'    => 1,
            'unit_price'  => 75.00,
            'total_price' => 75.00,
        ]);

        BillItem::create([
            'bill_id'     => $bill1->id,
            'item_type'   => 'Lab Test',
            'item_name'   => 'Comprehensive Lipid Profile',
            'quantity'    => 1,
            'unit_price'  => 40.00,
            'total_price' => 40.00,
        ]);

        BillItem::create([
            'bill_id'     => $bill1->id,
            'item_type'   => 'Pharmacy',
            'item_name'   => 'Amlodipine 5mg (30 Tablets)',
            'quantity'    => 1,
            'unit_price'  => 15.50,
            'total_price' => 15.50,
        ]);

        Payment::create([
            'bill_id'        => $bill1->id,
            'receipt_number' => 'REC-2026-001',
            'amount'         => 126.53,
            'payment_method' => 'Credit Card',
            'transaction_id' => 'TXN_VISA_98218731',
            'payment_date'   => now(),
            'received_by'    => 'Emily Watson',
        ]);
    }
}
