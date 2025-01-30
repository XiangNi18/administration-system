<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Account;

class BalanceSheet extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';
    protected static ?string $navigationLabel = 'Laporan Neraca';
    protected static string $view = 'filament.pages.balance-sheet';

    public $startDate;
    public $endDate;

    public function mount()
    {
        $this->startDate = now()->startOfMonth()->toDateString();
        $this->endDate = now()->endOfMonth()->toDateString();
    }

    protected function viewData(): array
    {
        // Ambil semua akun dengan total debit & kredit berdasarkan filter tanggal
        $accounts = Account::with(['journals' => function ($query) {
            $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
        }])->get();

        // Kelompokkan akun berdasarkan kategori
        $balances = [
            'Aktiva Lancar' => 0,
            'Aktiva Tidak Lancar' => 0,
            'Liabilitas Jangka Pendek' => 0,
            'Liabilitas Jangka Panjang' => 0,
            'Ekuitas' => 0,
        ];

        foreach ($accounts as $account) {
            $balance = $account->journals->sum('debit') - $account->journals->sum('kredit');

            switch ($account->type) {
                case 'Aktiva Lancar':
                    $balances['Aktiva Lancar'] += $balance;
                    break;
                case 'Aktiva Tidak Lancar':
                    $balances['Aktiva Tidak Lancar'] += $balance;
                    break;
                case 'Liabilitas Jangka Pendek':
                    $balances['Liabilitas Jangka Pendek'] += $balance;
                    break;
                case 'Liabilitas Jangka Panjang':
                    $balances['Liabilitas Jangka Panjang'] += $balance;
                    break;
                case 'Ekuitas':
                    $balances['Ekuitas'] += $balance;
                    break;
            }
        }

        $totalAssets = $balances['Aktiva Lancar'] + $balances['Aktiva Tidak Lancar'];
        $totalLiabilitiesAndEquity = $balances['Liabilitas Jangka Pendek'] + $balances['Liabilitas Jangka Panjang'] + $balances['Ekuitas'];

        return [
            'balances' => $balances,
            'totalAssets' => $totalAssets,
            'totalLiabilitiesAndEquity' => $totalLiabilitiesAndEquity,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ];
    }
}
