@extends('layouts.process')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        UiPath Process Monitoring
    </h2>
@endsection

@section('content')
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
        <table class="table-auto w-full text-left text-sm text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-2">Release Name</th>
                    <th class="px-4 py-2">State</th>
                    <th class="px-4 py-2">Start Time</th>
                    <th class="px-4 py-2">End Time</th>
                    <th class="px-4 py-2">Info</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jobs as $job)
                   <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700">
                        <td class="px-4 py-2">{{ $job['Release']['ProcessKey'] ?? '-' }}</td>
                        <td class="px-4 py-2">
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                @if($job['State'] === 'Successful') bg-green-100 text-green-800
                                @elseif($job['State'] === 'Faulted') bg-red-100 text-red-800
                                @elseif($job['State'] === 'Running') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $job['State'] }}
                            </span>
                        </td>
                        <td class="px-4 py-2">
                            {{ isset($job['StartTime']) ? \Carbon\Carbon::parse($job['StartTime'])->setTimezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s') : '-' }}
                        </td>
                        <td class="px-4 py-2">
                            {{ isset($job['EndTime']) ? \Carbon\Carbon::parse($job['StartTime'])->setTimezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s') : '-' }}
                        </td>
                        <td class="px-4 py-2">
                            {{ $job['Info'] ?? ($job['JobError']['Reason'] ?? '-') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center px-4 py-4">Tiada data ditemui</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection