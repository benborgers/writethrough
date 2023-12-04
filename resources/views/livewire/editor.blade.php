<div
    x-data
    x-init="$wire.content = localStorage.getItem('writethrough_content') ?? ''"
    x-effect="localStorage.setItem('writethrough_content', $wire.content)"
    class="grid grid-rows-[max-content,1fr] grid-cols-2 gap-y-4 gap-x-8 h-screen p-4 sm:p-8"
>
    <div class="col-span-2">
        <p class="text-gray-900 text-lg font-bold tracking-tight">
            writethrough
            @env('local')
                <span class="text-rose-600">dev</span>
            @endenv
        </p>
        <p class="text-gray-400 italic">Write broken German, get right German. ⌘Enter to fix.</p>
    </div>

    <textarea
        wire:model="content"
        placeholder="Write here..."
        x-on:keydown.meta.enter.prevent="$wire.fix()"
        class="w-full text-lg leading-relaxed p-4 border border-gray-200 rounded-2xl h-full shadow-sm focus:outline-none resize-none"
    ></textarea>

    <div class="relative bg-white text-lg leading-relaxed p-4 border border-gray-200 rounded-2xl shadow-sm overflow-scroll">
        <div wire:stream="fixed">{!! $fixed !!}</div>
        <div class="absolute top-2.5 left-2.5 bg-white shadow-sm border border-gray-100 p-1.5 rounded-lg" wire:loading>
            <div class="h-5 w-5 rounded-full border-2 border-emerald-500/30 border-t-emerald-500 animate-spin"></div>
        </div>
    </div>
</div>
