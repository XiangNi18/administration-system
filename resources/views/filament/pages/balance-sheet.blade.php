<x-filament::page>
    <form method="GET" class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <x-filament-forms::field-wrapper label="Tanggal Mulai" required>
                <input type="date" name="start_date" value="{{ request('start_date', $startDate) }}"
                    class="form-input" />
            </x-filament-forms::field-wrapper>
            <x-filament-forms::field-wrapper label="Tanggal Selesai" required>
                <input type="date" name="end_date" value="{{ request('end_date', $endDate) }}" class="form-input" />
            </x-filament-forms::field-wrapper>
        </div>
        <x-filament::button type="submit" class="mt-4">Tampilkan</x-filament::button>
    </form>

    <h2 class="text-xl font-bold mt-6">Laporan Neraca</h2>
    <div class="grid grid-cols-2 gap-8 mt-6">
        <div>
            <h3 class="text-lg font-bold">Aktiva</h3>
            <table class="table-auto w-full mt-4">
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th>Saldo (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Aktiva Lancar</td>
                        <td class="text-right">{{ number_format($balances['Aktiva Lancar'] ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Aktiva Tidak Lancar</td>
                        <td class="text-right">{{ number_format($balances['Aktiva Tidak Lancar'] ?? 0, 2) }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total Aktiva</th>
                        <th class="text-right">{{ number_format($totalAssets ?? 0, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div>
            <h3 class="text-lg font-bold">Kewajiban dan Ekuitas</h3>
            <table class="table-auto w-full mt-4">
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th>Saldo (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Liabilitas Jangka Pendek</td>
                        <td class="text-right">{{ number_format($balances['Liabilitas Jangka Pendek'] ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Liabilitas Jangka Panjang</td>
                        <td class="text-right">{{ number_format($balances['Liabilitas Jangka Panjang'] ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Ekuitas</td>
                        <td class="text-right">{{ number_format($balances['Ekuitas'] ?? 0, 2) }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total Kewajiban dan Ekuitas</th>
                        <th class="text-right">{{ number_format($totalLiabilitiesAndEquity ?? 0, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <p
        class="text-lg font-bold mt-6 {{ ($totalAssets ?? 0) === ($totalLiabilitiesAndEquity ?? 0) ? 'text-green-500' : 'text-red-500' }}">
        {{ ($totalAssets ?? 0) === ($totalLiabilitiesAndEquity ?? 0) ? 'Neraca Seimbang' : 'Neraca Tidak Seimbang' }}
    </p>
</x-filament::page>
