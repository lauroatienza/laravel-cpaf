<x-filament::page class="bg-[#18181B]">
    <div class="w-full max-w-3xl mx-auto bg-[#18181B] text-white shadow-md rounded-xl p-8">
        <!-- Profile -->
        <div class="flex flex-col items-center space-y-4">
            <img class="w-20 h-20 rounded-full border-2 border-amber-400"
                 src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name . ' ' . auth()->user()->last_name) }}&background=FBBF24&color=000"
                 alt="User Avatar">
            <h2 class="text-xl font-semibold">
                {{ auth()->user()->name }} 
                @if(auth()->user()->middle_name) {{ auth()->user()->middle_name }} @endif
                {{ auth()->user()->last_name }}
            </h2>
            <p class="text-gray-400 text-sm">{{ auth()->user()->email }}</p>
        </div>

        <!-- Two Column Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-6">
            @foreach([
                'Role' => auth()->user()->role,
                'Employment Status' => auth()->user()->employment_status,
                'Designation' => auth()->user()->designation,
                'Unit' => auth()->user()->unit,
                'Employment Type' => auth()->user()->fulltime_partime,
                'Highest Degree Attained' => auth()->user()->ms_phd,
                'Joined' => auth()->user()->created_at->format('F d, Y')
            ] as $label => $value)
            <div class="flex flex-col space-y-1"> <!-- Added space-y-1 for spacing -->
                <label class="text-sm text-gray-400 pb-1">{{ $label }}</label> <!-- Added pb-1 -->
                <p class="text-lg pt-1">{{ $value }}</p> <!-- Added pt-1 -->
            </div>
            @endforeach
        </div>
    </div>
</x-filament::page>
