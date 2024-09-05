@extends('layouts.app')

@section('content')


<div class="relative overflow-x-auto">
    <div class="text-2xl text-white mb-2" style="color:{{$brand->color}}">{{$brand->name }} Journal Overview</div>
    <div class="flex justify-between">
        <a href="{{route('dashboard.brands')}}" class="bg-red-500 text-white px-4 py-2 rounded mb-3">Back</a>
        <form action="{{ route('brand_export') }}" method="POST">
            @csrf
            <input type="hidden" name="brand_id" value="{{ $brand->id }}">
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded mb-3">Export to CSV</button>
    </div>
    </form>
    <div id="loading" class="fixed inset-0 flex items-center justify-center bg-gray-600 bg-opacity-50 z-50 hidden">
        <div class="loader border-t-4 border-b-4 border-white w-16 h-16 rounded-full animate-spin"></div>
    </div>
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Total Revenue
                </th>
                <th scope="col" class="px-6 py-3">
                    Total Food Costs
                </th>
                <th scope="col" class="px-6 py-3">
                    Total Labor Costs
                </th>
                <th scope="col" class="px-6 py-3">
                    Total Profit
                </th>
            </tr>
        </thead>
        <tbody>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <div class="font-bold"> ${{ number_format($brand->totalRevenue, 2) }}</div>
                </th>
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