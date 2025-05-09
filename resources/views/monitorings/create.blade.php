<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('System Monitoring') }}
        </h2>
    </x-slot>


    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <form action="{{ route('monitorings.store') }}" method="POST">
            @csrf

            <!-- Tarikh -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Date</label>
                <input type="date" name="date" value="{{ now()->format('Y-m-d') }}" readonly class="border rounded w-48 py-2 px-3">
            </div>

            <!-- Que Trigger -->
            <div class="mb-4">
                <label for="activity_que" class="block text-sm font-medium text-gray-700">Que Trigger</label>
                <select name="activity_que" class="border rounded w-48 py-2 px-3">
                    <option value="">-- Sila pilih proses --</option>
                    <option value="GCXP-0010">GCXP-0010</option>
                    <option value="GCXP-0011">GCXP-0011</option>
                    <option value="GCXP-0014">GCXP-0014</option>
                    <option value="GCXP-0022">GCXP-0022</option>
                    <option value="GNWT-0021">GNWT-0021</option>
                    <option value="GFIN-0014">GFIN-0014</option>
                    <option value="GFIN-0025">GFIN-0025</option>
                    <option value="GFIN-0030">GFIN-0030</option>
                    <option value="GFIN-0037">GFIN-0037</option>
                </select>
            

                <!-- Masa & Input -->
                <div id="time-slots-que" class="mt-4 hidden">
                    <h3 class="font-semibold mb-2">Aktiviti Que Trigger</h3>

                    @php
                        $timeSlots = ['8:30AM', '10:30AM', '12:00PM', '2:00PM', '3:30PM', '4:30PM'];
                    @endphp

@foreach ($timeSlots as $time)
<div class="mb-4">
    <label class="block text-sm font-semibold text-gray-700 mb-1">{{ $time }}</label>
    <div class="flex flex-wrap md:flex-nowrap gap-x-4 gap-y-4">
        <div>
            <label class="block text-sm text-gray-600">Total VM</label>
            <input type="text" name="activities[{{ $time }}][vm]" class="border rounded w-full py-2 px-3" placeholder="Contoh: VM123">
        </div>
        <div>
            <label class="block text-sm text-gray-600">Queues</label>
            <input type="text" name="activities[{{ $time }}][queue]" class="border rounded w-full py-2 px-3" placeholder="Contoh: GCXP-0010">
        </div>
        <div>
            <label class="block text-sm text-gray-600">Remark</label>
            <input type="text" name="activities[{{ $time }}][remark]" class="border rounded w-full py-2 px-3" placeholder="Catatan tambahan">
        </div>
    </div>
</div>
@endforeach
</div>
</div>


<!-- Time Trigger -->
<div class="mb-4">
    <label for="activity_time" class="block text-sm font-medium text-gray-700">Time Trigger</label>
    <select name="activity_time" class="border rounded w-48 py-2 px-3">
        <option value="">-- Sila Pilih Proses --</option>
            <option value="GCXP-0021">GCXP-0021</option>
            <option value="GFIN-0005">GFIN-0005</option>
            <option value="GFIN-0006/GFIN-0027">GFIN-0006/GFIN-0027</option>
            <option value="GFIN-0008">GFIN-0008</option>
            <option value="GITD-0074">GITD-0074</option>
            <option value="GFIN-0016">GFIN-0016</option>
            <option value="GFIN-0017">GFIN-0017</option>
            <option value="GFIN-0025">GFIN-0025</option>
            <option value="GFIN-0026">GFIN-0026</option>
            <option value="GFIN-0033">GFIN-0033</option>
            <option value="GITD-0005">GITD-0005</option>
            <option value="GITD-0006">GITD-0006</option>
            <option value="GITD-0062">GITD-0062</option>
            <option value="TMWS-0013">TMWS-0013</option>
    </select>


     <!-- Masa & Input -->
     <div id="time-slots-time" class="mt-4 hidden">
        <h3 class="font-semibold mb-2">Aktiviti Time Trigger</h3>

        @php
            $timeSlots = ['8:30AM', '10:30AM', '12:00PM', '2:00PM', '3:30PM', '4:30PM'];
        @endphp

        @foreach ($timeSlots as $time)
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">{{ $time }}</label>
                    <div class="flex flex-wrap md:flex-nowrap gap-x-4 gap-y-4">
                        <div>
                            <label class="block text-sm text-gray-600">Process</label>
                            <select name="activity_time" class="border rounded w-48 py-2 px-3">
                            <option value="">-- Sila pilih proses --</option>
                            <option value="OK">OK</option>
                            <option value="FAULT">FAULT</option>
                            <option value="ERROR">ERROR</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600">Remark</label>
                                <input type="text" name="activities[{{ $time }}][remark]" class="border rounded w-full py-2 px-3" placeholder="Catatan tambahan">
                        </div>
                    </div>
            </div>
        @endforeach
    </div>
</div>

            <!-- ✅ BUTTON SIMPAN DALAM FORM -->
            <div class="mt-4 text-left">
                <button type="submit" class="bg-white border border-black text-black px-4 py-2 rounded hover:bg-gray-100">
                     Save
                 </button>
            </div>
        </form>

        <!-- JS untuk paparkan slot masa -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const triggerDropdown = document.querySelector('select[name="activity_que"]');
                const timeSlotsDiv = document.getElementById('time-slots-que');

                triggerDropdown.addEventListener('change', function () {
                    if (this.value !== '') {
                        timeSlotsDiv.classList.remove('hidden');
                    } else {
                        timeSlotsDiv.classList.add('hidden');
                    }
                });
            });
        </script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const triggerDropdown = document.querySelector('select[name="activity_time"]');
        const timeSlotsDiv = document.getElementById('time-slots-time');

        triggerDropdown.addEventListener('change', function () {
            if (this.value !== '') {
                timeSlotsDiv.classList.remove('hidden');
            } else {
                timeSlotsDiv.classList.add('hidden');
            }
        });
    });
</script>

    </div>
</div>

</x-app-layout>
