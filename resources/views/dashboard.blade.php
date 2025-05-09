<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Monthly Roster Monitoring
        </h2>
    </x-slot>

    <style>
        .blinking-dot {
            display: inline-block;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: #f6a53b; /* blue-500 */
            animation: blink 1.5s infinite;
            margin-left: 4px;
            vertical-align: middle;
        }
        @keyframes blink {
            0% { opacity: 1; }
            50% { opacity: 0.3; }
            100% { opacity: 1; }
        }
    </style>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            <!-- Dropdown Bulan & Tahun -->
            <form method="GET" action="{{ route('dashboard') }}" class="mb-4 flex flex-wrap gap-3 items-center">
                <div class="flex items-center gap-2">
                    <select name="month" class="border rounded px-3 py-2 w-48">
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" {{ $month == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                            </option>
                        @endfor
                    </select>
            
                    <select name="year" class="border rounded px-3 py-2 w-48">
                        @for ($y = now()->year - 1; $y <= now()->year + 1; $y++)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>
            
                <button type="submit" class="bg-white text-black px-4 py-2 rounded border border-black hover:bg-gray-100 shadow">
                    Search
                </button>
            </form>

            <form method="POST" action="{{ route('roster.save') }}">
                @csrf

            <!-- Jadual -->
            <div class="bg-white shadow-sm sm:rounded-lg p-4">
                <div class="overflow-y-auto max-h-[500px] border rounded-md mt-4">
                    <table class="w-full table-auto border border-gray-300">
                        <thead>
                            <tr>
                                <th class="sticky top-0 bg-white z-10 border px-2 py-1">Date</th>
                                <th class="sticky top-0 bg-white z-10 border px-2 py-1">Day</th>
                                <th class="sticky top-0 bg-white z-10 border px-2 py-1">Week</th>
                                <th class="sticky top-0 bg-white z-10 border px-2 py-1">Morning Shift</th>
                                <th class="sticky top-0 bg-white z-10 border px-2 py-1">Afternoon Shift</th>
                            </tr>
                        </thead>
            <!-- Tbody-->
                        <tbody>
                            @foreach ($days as $index => $day)
                                @php
                                    $currentDate = now()->format('Y-m-d');
                                    $rowDate = \Carbon\Carbon::parse($day['date'])->format('Y-m-d');
                                    $isCurrentDate = $currentDate === $rowDate;
                                    $morningKey = $day['date'] . '_morning';
                                    $afternoonKey = $day['date'] . '_afternoon';
                                @endphp
                        
                                <tr class="{{ $isCurrentDate ? 'bg-blue-50' : '' }}">

                                    <td class="border px-2 py-1 {{ $isCurrentDate ? 'font-bold text-blue-600' : '' }}">
                                        <a href="{{ route('monitorings.create.withDate', ['date' => $day['date']]) }}" class="underline hover:text-blue-500">
                                            {{ $day['date'] }}
                                        </a>
                                        @if($isCurrentDate)
                                            <span class="blinking-dot"></span>
                                        @endif
                                    </td>
                                                                       


                                    <td class="border px-2 py-1 {{ $isCurrentDate ? 'font-bold text-blue-600' : '' }}">{{ $day['day'] }}</td>
                                    <td class="border px-2 py-1 {{ $isCurrentDate ? 'font-bold text-blue-600' : '' }}">Week {{ $day['week'] }}</td>
                        
                                   
                                    <!-- <input type="hidden" name="rosters[{{ $index }}][date]" value="{{ $day['date'] }}"> -->
                                   
                                    <input type="hidden" name="rosters[{{ $index }}][day]" value="{{ $day['day'] }}">
                                    <input type="hidden" name="rosters[{{ $index }}][week]" value="{{ $day['week'] }}">
                        
                                    <!-- Morning Shift -->
                                    <td class="border px-2 py-1">
                                        <select name="roster[{{ $day['date'] }}][morning]" class="w-full border rounded px-1 py-1 bg-green-100">
                                            <option value="">-- Select --</option>
                        
                                            @foreach ($staff as $name)
                                                <option value="{{ $name }}"
                                                    {{ isset($savedRosters[$morningKey]) && $savedRosters[$morningKey]->first()->staff_name == $name ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                        
                                            <option class="bg-yellow-200" value="Onleave"
                                                {{ isset($savedRosters[$morningKey]) && $savedRosters[$morningKey]->first()->staff_name == 'Onleave' ? 'selected' : '' }}>
                                                🟨 Onleave
                                            </option>
                                            <option class="bg-orange-200" value="Meeting"
                                                {{ isset($savedRosters[$morningKey]) && $savedRosters[$morningKey]->first()->staff_name == 'Meeting' ? 'selected' : '' }}>
                                                🟧 Meeting
                                            </option>
                                        </select>
                                    </td>
                        
                                    <!-- Afternoon Shift -->
                                    <td class="border px-2 py-1">
                                        <select name="roster[{{ $day['date'] }}][afternoon]" class="w-full border rounded px-1 py-1 bg-green-100">
                                            <option value="">-- Select --</option>
                        
                                            @foreach ($staff as $name)
                                                <option value="{{ $name }}"
                                                    {{ isset($savedRosters[$afternoonKey]) && $savedRosters[$afternoonKey]->first()->staff_name == $name ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                        
                                            <option class="bg-yellow-200" value="Onleave"
                                                {{ isset($savedRosters[$afternoonKey]) && $savedRosters[$afternoonKey]->first()->staff_name == 'Onleave' ? 'selected' : '' }}>
                                                🟨 Onleave
                                            </option>
                                            <option class="bg-orange-200" value="Meeting"
                                                {{ isset($savedRosters[$afternoonKey]) && $savedRosters[$afternoonKey]->first()->staff_name == 'Meeting' ? 'selected' : '' }}>
                                                🟧 Meeting
                                            </option>
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        
                       
                    </table>
                </div>

                <!-- Button Simpan -->
                    <div class="mt-4 text-right">
                        <button type="submit" class="bg-white border border-black text-black px-4 py-2 rounded hover:bg-gray-100">
                             Save
                         </button>
                    </div>
            </div>

            @if (session('success'))
                <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
                 {{ session('success') }}
                </div>
            @endif
        </form>

            </div>

            <p class="mt-4 text-sm text-gray-500">
                🟩 Available &nbsp;&nbsp; 🟨 Onleave &nbsp;&nbsp; 🟧 Meeting
            </p>
        </div>
    </div>
</x-app-layout>