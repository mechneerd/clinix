<?php

namespace App\Livewire\Clinic;

use Livewire\Component;
use App\Models\LedgerAccount;
use App\Models\FinancialTransaction;
use App\Models\Invoice;

class Ledger extends Component
{
    public function render()
    {
        $clinicId = auth()->user()->staff->clinic_id;

        return view('livewire.clinic.ledger', [
            'totalRevenue' => Invoice::where('clinic_id', $clinicId)->where('status', 'paid')->sum('total_amount'),
            'totalExpenses' => FinancialTransaction::where('clinic_id', $clinicId)->where('debit', '>', 0)->sum('debit'),
            'accounts' => LedgerAccount::where('clinic_id', $clinicId)->get(),
            'recentTransactions' => FinancialTransaction::with('account')
                ->where('clinic_id', $clinicId)
                ->latest()->take(10)->get(),
        ]);
    }
}
