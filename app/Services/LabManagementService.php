<?php

namespace App\Services;

use App\Models\Clinic;
use App\Models\Lab;
use App\Models\LabTest;
use App\Models\TestCategory;
use Illuminate\Support\Facades\DB;

class LabManagementService
{
    public function getClinicLabs(int $clinicId)
    {
        return Lab::where('clinic_id', $clinicId)
            ->withCount(['tests', 'orders'])
            ->latest()
            ->get();
    }

    public function createLab(Clinic $clinic, array $data): Lab
    {
        $data['clinic_id']             = $clinic->id;
        $data['user_subscription_id']  = $clinic->user_subscription_id;
        return Lab::create($data);
    }

    public function updateLab(Lab $lab, array $data): Lab
    {
        $lab->update($data);
        return $lab->fresh();
    }

    public function deleteLab(Lab $lab): void
    {
        $lab->delete();
    }

    public function getLabTests(int $labId, ?int $categoryId = null)
    {
        return LabTest::with('category')
            ->where('lab_id', $labId)
            ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
            ->latest()
            ->paginate(20);
    }

    public function createTest(Lab $lab, array $data): LabTest
    {
        $data['lab_id'] = $lab->id;
        return LabTest::create($data);
    }

    public function updateTest(LabTest $test, array $data): LabTest
    {
        $test->update($data);
        return $test->fresh();
    }

    public function getCategories(int $labId)
    {
        return TestCategory::where('lab_id', $labId)->orderBy('sort_order')->get();
    }

    public function createCategory(int $labId, array $data): TestCategory
    {
        return TestCategory::create(['lab_id' => $labId, ...$data]);
    }
}
