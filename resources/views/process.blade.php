@extends('layouts.process')

<meta http-equiv="refresh" content="300">

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        UiPath Process Monitoring
    </h2>
@endsection

@section('content')
    {{-- DataTables CSS CDN --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
        <table id="jobsTable" class="display table-auto w-full text-left text-sm text-gray-700 dark:text-gray-400">
            <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-2 py-2 w-1/6">Release Name</th>
                    <th class="px-2 py-2 w-1/12">State</th>
                    <th class="px-2 py-2 w-1/6">Start Time</th>
                    <th class="px-2 py-2 w-1/6">End Time</th>
                    <th class="px-2 py-2 w-1/6">Info</th>
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
                            {{ isset($job['EndTime']) ? \Carbon\Carbon::parse($job['EndTime'])->setTimezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s') : '-' }}
                        </td>
                        <td class="px-4 py-2">
                            {{ $job['Info'] ?? ($job['JobError']['Reason'] ?? '-') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center px-4 py-4">Tiada data ditemui</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- jQuery + DataTables JS CDN --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#jobsTable').DataTable({
                "pageLength": 10,
                "order": [], // disable initial sorting
                "language": {
                    "search": "Search:",
                    "lengthMenu": "Show_MENU_",
                    "zeroRecords": "No Records Found",
                    "info": "Showing _START_ to _END_ from _TOTAL_ records",
                    "infoEmpty": "Tiada rekod",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Seterusnya",
                        "previous": "Sebelumnya"
                    }
                }
            });
        });
    </script>
@endsection
