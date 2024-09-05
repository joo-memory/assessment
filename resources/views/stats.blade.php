@extends('layouts.app')

@section('content')


<div class="relative overflow-x-auto">
    <div class="text-2xl text-white mb-2">Store "{{$journals->first()->store->number }}" Journals</div>
    <div class="flex justify-between">
        <a href="{{route('dashboard.stores')}}" class="bg-red-500 text-white px-4 py-2 rounded mb-3">Back</a>
        <form action="{{ route('export') }}" method="POST">
            @csrf
            <input type="hidden" name="store_id" value="{{ $storeId }}">
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded mb-3">Export to CSV</button>
        </form>
    </div>
    <div id="loading" class="fixed inset-0 flex items-center justify-center bg-gray-600 bg-opacity-50 z-50 hidden">
        <div class="loader border-t-4 border-b-4 border-white w-16 h-16 rounded-full animate-spin"></div>
    </div>
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Date
                </th>
                <th scope="col" class="px-6 py-3">
                    Revenue
                </th>
                <th scope="col" class="px-6 py-3">
                    Food Costs
                </th>
                <th scope="col" class="px-6 py-3">
                    Labor Costs
                </th>
                <th scope="col" class="px-6 py-3">
                    Profit
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($journals as $journal)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{$journal->date}}
                </th>
                <td class="px-6 py-4">
                    ${{ number_format($journal->revenue, 2) }}
                </td>
                <td class="px-6 py-4">
                    ${{ number_format($journal->food_cost, 2) }}
                </td>
                <td class="px-6 py-4">
                    ${{ number_format($journal->labor_cost, 2) }}
                </td>
                <td class="px-6 py-4">
                    ${{ number_format($journal->profit, 2) }}
                </td>
            </tr>
            @endforeach
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                   Total
                </th>
                <td class="px-6 py-4">
                    <div class="font-bold">${{ number_format($totalRevenue, 2) }}</div>
                </td>
                <td class="px-6 py-4">
                    <div class="font-bold">${{ number_format($totalFoodCost, 2) }}</div>
                </td>
                <td class="px-6 py-4">
                    <div class="font-bold">${{ number_format($totalLaborCost, 2) }}</div>
                </td>
                <td class="px-6 py-4">
                    <div class="font-bold">${{ number_format($totalProfit, 2) }}</div>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');
        const loading = document.getElementById('loading');

        form.addEventListener('submit', function () {
            loading.classList.remove('hidden');
        });
    });
</script>

@endsection