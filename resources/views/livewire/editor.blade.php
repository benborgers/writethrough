<div
    x-data
    x-init="() => {
        $wire.content = localStorage.getItem('writethrough_content') ?? ''
        $wire.language = localStorage.getItem('writethrough_language') ?? 'German'
        $wire.save()
    }"
    x-effect="() => {
        localStorage.setItem('writethrough_content', $wire.content)
        localStorage.setItem('writethrough_language', $wire.language)
    }"
    class="grid grid-rows-[max-content,1fr] grid-cols-2 gap-y-4 gap-x-8 h-screen p-4 sm:p-8"
>
    <div class="col-span-2 flex items-end justify-between">
        <div>
            <p class="text-gray-900 text-lg font-bold tracking-tight">
                writethrough
                @env('local')
                    <span class="text-rose-600">dev</span>
                @endenv
            </p>
            <p class="text-gray-400 italic">
                Write broken {{ $language }}, get right {{ $language }}.
                ⌘Enter to fix.
                Made by <a href="https://ben.page" target="_blank" class="underline decoration-gray-300">Ben Borgers</a>.
            </p>
        </div>
        <div>
            <p class="text-right text-sm font-medium text-gray-500">Language</p>
            <select
                wire:model.live="language"
                class="py-0 pl-0 pr-8 border-none -mr-3 bg-transparent font-semibold focus:ring-0 text-right"
            >
                <option>German</option>
                <option>French</option>
                <option>Spanish</option>
                <option>Mandarin</option>
            </select>
        </div>
    </div>

    <textarea
        wire:model="content"
        placeholder="Write here..."
        x-on:keydown.meta.enter.prevent="$wire.fix()"
        class="w-full text-lg leading-relaxed p-4 border border-gray-200 rounded-2xl h-full shadow-sm focus:outline-none resize-none placeholder-gray-400 focus:border-gray-200 focus:ring-0"
    ></textarea>

    <div class="relative bg-white text-lg leading-relaxed p-4 border border-gray-200 rounded-2xl shadow-sm overflow-scroll">
        <div wire:stream="fixed">{!! $fixed !!}</div>
        <div class="absolute top-2.5 left-2.5 bg-white shadow-sm border border-gray-100 p-1.5 rounded-lg" wire:loading>
            <div class="h-5 w-5 rounded-full border-2 border-emerald-500/30 border-t-emerald-500 animate-spin"></div>
        </div>
    </div>
</div>
