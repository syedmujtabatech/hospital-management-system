<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Payment;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BillingController extends Controller
{
    public function index(Request $request)
    {
        $query = Bill::with(['patient', 'payments']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('bill_number', 'like', "%{$search}%")
                  ->orWhereHas('patient', function($pq) use ($search) {
                      $pq->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%")
                         ->orWhere('mrn', 'like', "%{$search}%");
                  });
            });
        }

        $bills = $query->orderByDesc('bill_date')->paginate(15)->withQueryString();

        $stats = [
            'total_billed' => Bill::sum('total_amount'),
            'total_paid' => Bill::sum('paid_amount'),
            'total_due' => Bill::sum('due_amount'),
            'unpaid_count' => Bill::where('status', 'unpaid')->count(),
        ];

        return view('billing.index', compact('bills', 'stats'));
    }

    public function create(Request $request)
    {
        $patients = Patient::orderBy('first_name')->get();
        $selectedPatientId = $request->query('patient_id');

        return view('billing.create', compact('patients', 'selectedPatientId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'bill_date' => 'required|date',
            'discount_amount' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'paid_now' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|in:cash,credit_card,debit_card,bank_transfer,online',
            // items
            'item_description' => 'required|array|min:1',
            'item_description.*' => 'required|string',
            'item_type' => 'required|array',
            'quantity' => 'required|array',
            'unit_price' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $count = Bill::whereDate('bill_date', Carbon::today())->count();
            $billNumber = 'INV-' . date('Ymd') . '-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);

            // Calculate subtotal
            $subtotal = 0;
            $itemsData = [];
            foreach ($request->item_description as $i => $desc) {
                $qty = (int) ($request->quantity[$i] ?? 1);
                $price = (float) ($request->unit_price[$i] ?? 0);
                $total = $qty * $price;
                $subtotal += $total;

                $itemsData[] = [
                    'description' => $desc,
                    'item_type' => $request->item_type[$i] ?? 'other',
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'total_price' => $total,
                ];
            }

            $discount = (float) ($request->discount_amount ?? 0);
            $tax = (float) ($request->tax_amount ?? 0);
            $totalAmount = max(0, ($subtotal - $discount) + $tax);
            $paidNow = (float) ($request->paid_now ?? 0);
            $dueAmount = max(0, $totalAmount - $paidNow);

            $status = 'unpaid';
            if ($dueAmount == 0 && $totalAmount > 0) {
                $status = 'paid';
            } elseif ($paidNow > 0 && $dueAmount > 0) {
                $status = 'partial';
            }

            $bill = Bill::create([
                'bill_number' => $billNumber,
                'patient_id' => $request->patient_id,
                'bill_date' => $request->bill_date,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'total_amount' => $totalAmount,
                'paid_amount' => $paidNow,
                'due_amount' => $dueAmount,
                'status' => $status,
                'notes' => $request->notes,
            ]);

            // Save items
            foreach ($itemsData as $item) {
                $item['bill_id'] = $bill->id;
                BillItem::create($item);
            }

            // Save initial payment if paid now
            if ($paidNow > 0) {
                $countPay = Payment::count();
                $receiptNo = 'REC-' . date('Ymd') . '-' . str_pad($countPay + 1, 3, '0', STR_PAD_LEFT);

                Payment::create([
                    'bill_id' => $bill->id,
                    'patient_id' => $bill->patient_id,
                    'receipt_number' => $receiptNo,
                    'amount' => $paidNow,
                    'payment_date' => $request->bill_date,
                    'payment_method' => $request->payment_method ?? 'cash',
                    'notes' => 'Initial payment upon invoice creation.',
                ]);
            }

            DB::commit();

            return redirect()->route('billing.show', $bill->id)
                ->with('success', "Invoice {$bill->bill_number} generated successfully!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error creating invoice: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $bill = Bill::with(['patient', 'items', 'payments'])->findOrFail($id);
        return view('billing.show', compact('bill'));
    }

    public function printInvoice($id)
    {
        $bill = Bill::with(['patient', 'items', 'payments'])->findOrFail($id);
        return view('billing.invoice_print', compact('bill'));
    }

    public function addPayment(Request $request, $id)
    {
        $bill = Bill::findOrFail($id);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $bill->due_amount,
            'payment_method' => 'required|in:cash,credit_card,debit_card,bank_transfer,online',
            'transaction_id' => 'nullable|string|max:100',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $countPay = Payment::count();
            $receiptNo = 'REC-' . date('Ymd') . '-' . str_pad($countPay + 1, 3, '0', STR_PAD_LEFT);

            Payment::create([
                'bill_id' => $bill->id,
                'patient_id' => $bill->patient_id,
                'receipt_number' => $receiptNo,
                'amount' => $validated['amount'],
                'payment_date' => $validated['payment_date'],
                'payment_method' => $validated['payment_method'],
                'transaction_reference' => $validated['transaction_id'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            $newPaid = $bill->paid_amount + $validated['amount'];
            $newDue = max(0, $bill->total_amount - $newPaid);
            $newStatus = ($newDue == 0) ? 'paid' : 'partial';

            $bill->update([
                'paid_amount' => $newPaid,
                'due_amount' => $newDue,
                'status' => $newStatus,
            ]);

            DB::commit();

            return back()->with('success', "Payment of $" . number_format($validated['amount'], 2) . " recorded. Receipt: {$receiptNo}");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Payment recording failed: ' . $e->getMessage());
        }
    }
}
