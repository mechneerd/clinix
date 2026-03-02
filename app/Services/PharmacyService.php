<?php

namespace App\Services;

use App\Models\Clinic;
use App\Models\Medicine;
use App\Models\MedicineCategory;
use App\Models\Pharmacy;
use App\Models\StockMovement;

class PharmacyService
{
    public function getClinicPharmacies(int $clinicId)
    {
        return Pharmacy::where('clinic_id', $clinicId)
            ->withCount(['medicines', 'sales'])
            ->latest()
            ->get();
    }

    public function createPharmacy(Clinic $clinic, array $data): Pharmacy
    {
        $data['clinic_id']            = $clinic->id;
        $data['user_subscription_id'] = $clinic->user_subscription_id;
        return Pharmacy::create($data);
    }

    public function updatePharmacy(Pharmacy $pharmacy, array $data): Pharmacy
    {
        $pharmacy->update($data);
        return $pharmacy->fresh();
    }

    public function getMedicines(int $pharmacyId, ?int $categoryId = null, ?string $search = null)
    {
        return Medicine::with('category')
            ->where('pharmacy_id', $pharmacyId)
            ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
            ->when($search,     fn($q) => $q->where('name','like',"%$search%")
                ->orWhere('generic_name','like',"%$search%"))
            ->latest()
            ->paginate(20);
    }

    public function addMedicine(Pharmacy $pharmacy, array $data): Medicine
    {
        $data['pharmacy_id'] = $pharmacy->id;
        $medicine = Medicine::create($data);

        StockMovement::create([
            'medicine_id'   => $medicine->id,
            'pharmacy_id'   => $pharmacy->id,
            'type'          => 'purchase',
            'quantity'      => $medicine->current_stock,
            'stock_before'  => 0,
            'stock_after'   => $medicine->current_stock,
            'reason'        => 'Initial stock entry',
            'created_by'    => auth()->id(),
        ]);

        return $medicine;
    }

    public function updateMedicine(Medicine $medicine, array $data): Medicine
    {
        $oldStock = $medicine->current_stock;
        $medicine->update($data);

        if (isset($data['current_stock']) && $data['current_stock'] != $oldStock) {
            StockMovement::create([
                'medicine_id'  => $medicine->id,
                'pharmacy_id'  => $medicine->pharmacy_id,
                'type'         => 'adjustment',
                'quantity'     => abs($data['current_stock'] - $oldStock),
                'stock_before' => $oldStock,
                'stock_after'  => $data['current_stock'],
                'reason'       => 'Manual stock adjustment',
                'created_by'   => auth()->id(),
            ]);
        }

        return $medicine->fresh();
    }

    public function getLowStockMedicines(int $pharmacyId)
    {
        return Medicine::where('pharmacy_id', $pharmacyId)
            ->where('is_active', true)
            ->whereColumn('current_stock', '<=', 'reorder_level')
            ->with('category')
            ->get();
    }

    public function getStats(int $pharmacyId): array
    {
        return [
            'total_medicines' => Medicine::where('pharmacy_id', $pharmacyId)->count(),
            'low_stock'       => Medicine::where('pharmacy_id', $pharmacyId)->whereColumn('current_stock','<=','reorder_level')->count(),
            'out_of_stock'    => Medicine::where('pharmacy_id', $pharmacyId)->where('current_stock', 0)->count(),
            'categories'      => MedicineCategory::where('pharmacy_id', $pharmacyId)->count(),
        ];
    }
}
