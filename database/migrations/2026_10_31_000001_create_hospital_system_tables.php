<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Departments
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('head_of_department')->nullable();
            $table->text('description')->nullable();
            $table->string('icon')->default('bx-plus-medical');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 2. Doctors
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('department_id')->constrained('departments')->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('specialization');
            $table->string('qualification')->nullable();
            $table->string('license_number')->nullable();
            $table->decimal('consultation_fee', 10, 2)->default(50.00);
            $table->string('schedule_days')->default('Mon, Tue, Wed, Thu, Fri');
            $table->string('schedule_hours')->default('09:00 AM - 02:00 PM');
            $table->string('room_number')->nullable();
            $table->string('room_no')->nullable();
            $table->text('bio')->nullable();
            $table->string('avatar')->nullable();
            $table->string('status')->default('active'); // supports 'active', 'inactive', '1'
            $table->timestamps();
        });

        // 3. Patients
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('mrn')->unique(); // Medical Record Number e.g. MRN-2026-0001
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_of_birth')->nullable();
            $table->integer('age')->nullable();
            $table->string('gender')->default('male'); // 'male', 'female', 'other'
            $table->string('blood_group', 5)->nullable();
            $table->string('phone');
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->text('allergies')->nullable();
            $table->text('chronic_conditions')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 4. Rooms / Wards (IPD Beds)
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_number')->unique();
            $table->string('ward_name');
            $table->string('room_type')->default('General Ward');
            $table->decimal('daily_charge', 10, 2)->default(100.00);
            $table->integer('capacity')->default(1);
            $table->string('status')->default('available'); // 'available', 'occupied', 'maintenance'
            $table->timestamps();
        });

        // 5. Appointments
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('appointment_number')->unique();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->date('appointment_date');
            $table->string('time_slot');
            $table->string('priority')->default('normal'); // 'normal', 'urgent', 'emergency'
            $table->text('problem_description')->nullable();
            $table->string('status')->default('scheduled'); // 'scheduled', 'confirmed', 'completed', 'cancelled'
            $table->timestamps();
        });

        // 6. Clinical Encounters (OPD Visits)
        Schema::create('encounters', function (Blueprint $table) {
            $table->id();
            $table->string('encounter_number')->nullable();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->foreignId('appointment_id')->nullable()->constrained('appointments')->nullOnDelete();
            $table->string('encounter_type')->default('OPD');
            $table->date('encounter_date')->nullable();
            $table->dateTime('visit_date')->nullable();
            $table->text('chief_complaint')->nullable();
            $table->text('symptoms')->nullable();
            $table->string('blood_pressure')->nullable();
            $table->string('bp')->nullable();
            $table->string('pulse_rate')->nullable();
            $table->string('pulse')->nullable();
            $table->string('temperature')->nullable();
            $table->string('respiratory_rate')->nullable();
            $table->string('spo2')->nullable();
            $table->string('weight')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('treatment_notes')->nullable();
            $table->text('doctor_advice')->nullable();
            $table->string('status')->default('completed');
            $table->timestamps();
        });

        // 7. Prescriptions
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->string('prescription_number')->unique();
            $table->foreignId('encounter_id')->nullable()->constrained('encounters')->nullOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->date('prescription_date')->nullable();
            $table->text('notes')->nullable();
            $table->text('clinical_notes')->nullable();
            $table->timestamps();
        });

        // 8. Prescription Items
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')->constrained('prescriptions')->cascadeOnDelete();
            $table->unsignedBigInteger('medicine_id')->nullable();
            $table->string('medicine_name')->nullable();
            $table->string('dosage')->nullable();
            $table->string('frequency')->nullable();
            $table->string('duration')->nullable();
            $table->string('instructions')->nullable();
            $table->timestamps();
        });

        // 9. IPD Admissions
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();
            $table->string('admission_number')->nullable();
            $table->string('ipd_number')->nullable();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();
            $table->dateTime('admission_date');
            $table->dateTime('discharge_date')->nullable();
            $table->text('reason')->nullable();
            $table->text('admission_reason')->nullable();
            $table->text('initial_diagnosis')->nullable();
            $table->text('discharge_notes')->nullable();
            $table->text('discharge_summary')->nullable();
            $table->string('discharge_condition')->nullable();
            $table->string('condition_on_discharge')->nullable();
            $table->decimal('daily_rate', 10, 2)->default(100.00);
            $table->decimal('initial_deposit', 10, 2)->default(0.00);
            $table->string('status')->default('admitted'); // 'admitted', 'discharged', 'transferred'
            $table->timestamps();
        });

        // 10. Medicines (Pharmacy Catalog)
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('generic_name')->nullable();
            $table->string('category')->default('Tablet');
            $table->string('dosage_form')->nullable();
            $table->string('batch_number')->nullable();
            $table->decimal('cost_price', 10, 2)->default(0.00);
            $table->decimal('purchase_price', 10, 2)->default(0.00);
            $table->decimal('selling_price', 10, 2)->default(0.00);
            $table->decimal('sale_price', 10, 2)->default(0.00);
            $table->integer('stock_quantity')->default(0);
            $table->integer('alert_quantity')->default(20);
            $table->date('expiry_date')->nullable();
            $table->string('manufacturer')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 11. Laboratory Tests Catalog
        Schema::create('lab_tests', function (Blueprint $table) {
            $table->id();
            $table->string('test_name');
            $table->string('test_code')->unique();
            $table->string('category')->default('Pathology');
            $table->decimal('standard_cost', 10, 2)->default(30.00);
            $table->decimal('price', 10, 2)->default(30.00);
            $table->string('normal_range')->nullable();
            $table->string('reference_range')->nullable();
            $table->string('sample_type')->nullable()->default('Whole Blood / Serum');
            $table->string('unit')->nullable();
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 12. Laboratory Orders
        Schema::create('lab_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->foreignId('lab_test_id')->constrained('lab_tests')->cascadeOnDelete();
            $table->date('order_date');
            $table->string('priority')->default('routine');
            $table->text('clinical_notes')->nullable();
            $table->dateTime('sample_collected_at')->nullable();
            $table->dateTime('result_date')->nullable();
            $table->text('result_value')->nullable();
            $table->text('result_values')->nullable();
            $table->text('result_notes')->nullable();
            $table->text('technician_remarks')->nullable();
            $table->string('status')->default('pending'); // 'pending', 'sample_collected', 'in_progress', 'completed', 'cancelled'
            $table->timestamps();
        });

        // 13. Bills (Consolidated Invoices)
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->string('bill_number')->nullable();
            $table->string('invoice_number')->nullable();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('encounter_id')->nullable()->constrained('encounters')->nullOnDelete();
            $table->foreignId('admission_id')->nullable()->constrained('admissions')->nullOnDelete();
            $table->date('bill_date');
            $table->date('due_date')->nullable();
            $table->decimal('subtotal', 10, 2)->default(0.00);
            $table->decimal('discount', 10, 2)->default(0.00);
            $table->decimal('tax', 10, 2)->default(0.00);
            $table->decimal('tax_percentage', 5, 2)->default(0.00);
            $table->decimal('total_amount', 10, 2)->default(0.00);
            $table->decimal('net_amount', 10, 2)->default(0.00);
            $table->decimal('paid_amount', 10, 2)->default(0.00);
            $table->decimal('due_amount', 10, 2)->default(0.00);
            $table->string('status')->default('unpaid'); // 'paid', 'partial', 'unpaid'
            $table->string('payment_status')->default('Unpaid');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 14. Bill Line Items
        Schema::create('bill_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bill_id')->constrained('bills')->cascadeOnDelete();
            $table->string('item_type')->default('consultation');
            $table->string('item_name')->nullable();
            $table->string('description')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2)->default(0.00);
            $table->decimal('total_price', 10, 2)->default(0.00);
            $table->timestamps();
        });

        // 15. Payments
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bill_id')->constrained('bills')->cascadeOnDelete();
            $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
            $table->string('receipt_number')->unique();
            $table->decimal('amount', 10, 2);
            $table->string('payment_method')->default('cash');
            $table->string('transaction_id')->nullable();
            $table->string('transaction_reference')->nullable();
            $table->dateTime('payment_date');
            $table->string('received_by')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('bill_items');
        Schema::dropIfExists('bills');
        Schema::dropIfExists('lab_orders');
        Schema::dropIfExists('lab_tests');
        Schema::dropIfExists('medicines');
        Schema::dropIfExists('admissions');
        Schema::dropIfExists('prescription_items');
        Schema::dropIfExists('prescriptions');
        Schema::dropIfExists('encounters');
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('patients');
        Schema::dropIfExists('doctors');
        Schema::dropIfExists('departments');
    }
};
