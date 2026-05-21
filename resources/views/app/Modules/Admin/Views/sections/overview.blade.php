<!-- Analytics Overview -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="glass-card p-6 inner-shadow">
        <div class="text-gray-500 text-[10px] font-bold uppercase mb-1">Total Users</div>
        <div class="text-3xl font-black">12,482</div>
        <div class="text-xs text-green-500 mt-2 font-bold">+12% this week</div>
    </div>
    <div class="glass-card p-6 inner-shadow">
        <div class="text-gray-500 text-[10px] font-bold uppercase mb-1">Total Files</div>
        <div class="text-3xl font-black">842,109</div>
        <div class="text-xs text-red-500 mt-2 font-bold">4.2 TB storage</div>
    </div>
    <div class="glass-card p-6 inner-shadow">
        <div class="text-gray-500 text-[10px] font-bold uppercase mb-1">Active Streams</div>
        <div class="text-3xl font-black">1,842</div>
        <div class="text-xs text-blue-500 mt-2 font-bold">Bandwidth: 450Mbps</div>
    </div>
    <div class="glass-card p-6 inner-shadow border-red-500/20">
        <div class="text-gray-500 text-[10px] font-bold uppercase mb-1">Security Health</div>
        <div class="text-3xl font-black text-green-500">EXCELLENT</div>
        <div class="text-xs text-gray-500 mt-2">No active threats</div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <div class="glass-card p-8 min-h-[300px] flex flex-col">
        <h3 class="font-bold mb-6 flex items-center justify-between">
            <span>Domain Traffic Distribution</span>
            <span class="text-[10px] glass px-2 py-1 rounded text-gray-400">LIVE</span>
        </h3>
        <div class="flex-1 flex items-end gap-2 px-2">
            @for($i=0; $i<20; $i++)
            <div class="flex-1 bg-red-600/20 border-t border-red-500/40 rounded-t-sm" style="height: {{ rand(20, 90) }}%"></div>
            @endfor
        </div>
        <div class="flex justify-between mt-4 text-[10px] text-gray-600 font-bold uppercase">
            <span>Primary Node</span>
            <span>Ghost Hop 04</span>
            <span>Storage Node Alpha</span>
        </div>
    </div>

    <div class="glass-card p-8 min-h-[300px]">
        <h3 class="font-bold mb-6">Recent System Logs</h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between text-xs border-b border-white/5 pb-2">
                <span class="text-gray-400">New Pro User Signup: user_842</span>
                <span class="text-gray-600">2 mins ago</span>
            </div>
            <div class="flex items-center justify-between text-xs border-b border-white/5 pb-2">
                <span class="text-red-400 font-bold">Bot Detection: IP 182.xx.xx blocked</span>
                <span class="text-gray-600">14 mins ago</span>
            </div>
            <div class="flex items-center justify-between text-xs border-b border-white/5 pb-2">
                <span class="text-blue-400">Node Rotation: Switching to x-jump.top</span>
                <span class="text-gray-600">1 hour ago</span>
            </div>
        </div>
    </div>
</div>
