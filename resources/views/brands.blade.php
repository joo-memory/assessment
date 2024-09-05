@extends('layouts.app')


@section('content')

<div class="mt-8 mb-4 flex flex-col md:flex-row justify-left gap-5">
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-4 w-full max-w-lg"> <!-- Adjust width and padding -->
        <div class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
            Revenue Chart
        </div>
        <div class="mt-2">
            <canvas id="brandRevenueChart" class="w-full h-64"></canvas> <!-- Set height to make it smaller -->
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-4 w-full max-w-lg"> <!-- Adjust width and padding -->
        <div class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
            Profit Chart
        </div>
        <div class="mt-2">
            <canvas id="brandProfitChart" class="w-full h-64"></canvas> <!-- Set height to make it smaller -->
        </div>
    </div>
</div>


    <div class="relative overflow-x-auto">
        <div class="text-2xl text-white mb-2">Brands</div>
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Brands
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Profit
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($brands as $brand)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white ">
                            <a href="{{ route('dashboard.brand_stats', ['id' => $brand->id]) }}"
                                style="color:{{ $brand->color }}"> {{ $brand->name }}</a>
                        </th>
                        <td class="px-6 py-4">
                            ${{ number_format($brand->total_profit, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Get data from Blade to JavaScript
        const brands = @json($brands->pluck('name'));
        const totalProfits = @json($brands->pluck('total_profit'));
        const totalRevenue = @json($brands->pluck('total_revenue'));
        const colors = @json($brands->pluck('color')); // Get colors for each brand

        // Chart.js setup
        const ctx = document.getElementById('brandProfitChart').getContext('2d');
        const brandProfitChart = new Chart(ctx, {
            type: 'bar', // You can change to 'line', 'pie', etc.
            data: {
                labels: brands, // X-axis labels (brand names)
                datasets: [{
                    label: 'Total Profit',
                    data: totalProfits, // Y-axis data (total profit)
                    backgroundColor: colors, // Use brand colors as background
                    borderColor: colors.map(color => color), // Use the same color for borders
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                }
            }
        });

        const ctx2 = document.getElementById('brandRevenueChart').getContext('2d');
        const brandRevenueChart = new Chart(ctx2, {
            type: 'bar', // You can change to 'line', 'pie', etc.
            data: {
                labels: brands, // X-axis labels (brand names)
                datasets: [{
                    label: 'Total Revenue',
                    data: totalRevenue, // Y-axis data (total profit)
                    backgroundColor: colors, // Use brand colors as background
                    borderColor: colors.map(color => color), // Use the same color for borders
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                }
            }
        });
    </script>
@endsection
