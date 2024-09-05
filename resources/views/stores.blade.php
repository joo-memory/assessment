@extends('layouts.app')

@section('content')


<div class="relative overflow-x-auto">
    <div class="text-2xl text-white mb-2">My Stores</div>
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Store Number
                </th>
                <th scope="col" class="px-6 py-3">
                    Brands
                </th>
                <th scope="col" class="px-6 py-3">
                    Address
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($stores as $store)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <a href="{{route('dashboard.stats',['id' => $store->id])}}" class="text-green-500">{{$store->number}}</a>
                </th>
                <td class="px-6 py-4">
                    {{$store->brand->name}}
                </td>
                <td class="px-6 py-4">
                    {{$store->address." ".$store->city.", ".$store->state." ".$store->zip_code}}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection