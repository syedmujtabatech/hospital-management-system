<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;
use Carbon\Carbon;

class PharmacyController extends Controller
{
    public function index(Request $request)
    {
        $query = Medicine::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('generic_name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('batch_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'low') {
                $query->whereColumn('stock_quantity', '<=', 'alert_quantity');
            } elseif ($request->stock_status === 'out') {
                $query->where('stock_quantity', 0);
            } elseif ($request->stock_status === 'in') {
                $query->whereColumn('stock_quantity', '>', 'alert_quantity');
            }
        }

        $medicines = $query->orderBy('name')->paginate(15)->withQueryString();
        $categories = Medicine::select('category')->distinct()->pluck('category');

        $stats = [
            'total_medicines' => Medicine::count(),
            'total_stock' => Medicine::sum('stock_quantity'),
            'low_stock' => Medicine::whereColumn('stock_quantity', '<=', 'alert_quantity')->count(),
            'expiring_soon' => Medicine::where('expiry_date', '<=', Carbon::today()->addMonths(2))->count(),
        ];

        return view('pharmacy.index', compact('medicines', 'categories', 'stats'));
    }

    public function create()
    {
        return view('pharmacy.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'generic_name' => 'nullable|string|max:150',
            'category' => 'required|string|max:100',
            'dosage_form' => 'nullable|string|max:50',
            'batch_number' => 'required|string|max:100',
            'expiry_date' => 'required|date',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'alert_quantity' => 'required|integer|min:0',
            'manufacturer' => 'nullable|string|max:150',
        ]);

        Medicine::create($validated);

        return redirect()->route('pharmacy.index')
            ->with('success', "Medicine '{$validated['name']}' added to pharmacy inventory.");
    }

    public function edit($id)
    {
        $medicine = Medicine::findOrFail($id);
        return view('pharmacy.edit', compact('medicine'));
    }

    public function update(Request $request, $id)
    {
        $medicine = Medicine::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'generic_name' => 'nullable|string|max:150',
            'category' => 'required|string|max:100',
            'dosage_form' => 'nullable|string|max:50',
            'batch_number' => 'required|string|max:100',
            'expiry_date' => 'required|date',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'alert_quantity' => 'required|integer|min:0',
            'manufacturer' => 'nullable|string|max:150',
        ]);

        $medicine->update($validated);

        return redirect()->route('pharmacy.index')
            ->with('success', "Medicine '{$medicine->name}' updated successfully.");
    }

    public function addStock(Request $request, $id)
    {
        $medicine = Medicine::findOrFail($id);

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'batch_number' => 'nullable|string|max:100',
            'expiry_date' => 'nullable|date',
        ]);

        $medicine->increment('stock_quantity', $validated['quantity']);

        if (!empty($validated['batch_number'])) {
            $medicine->batch_number = $validated['batch_number'];
        }
        if (!empty($validated['expiry_date'])) {
            $medicine->expiry_date = $validated['expiry_date'];
        }
        $medicine->save();

        return back()->with('success', "Added {$validated['quantity']} units to {$medicine->name}. Current stock: {$medicine->stock_quantity}");
    }

    public function destroy($id)
    {
        $medicine = Medicine::findOrFail($id);
        $medicine->delete();

        return redirect()->route('pharmacy.index')
            ->with('success', 'Medicine deleted.');
    }
}
