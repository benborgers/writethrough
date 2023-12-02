<div
    x-data
    x-init="$wire.content = localStorage.getItem('writethrough_content')"
    x-effect="localStorage.setItem('writethrough_content', $wire.content)"
    class="grid grid-rows-[max-content,1fr] grid-cols-2 gap-y-4 gap-x-8 min-h-screen p-4 sm:p-8"
>
    <div class="col-span-2">
        <p class="text-gray-900 text-lg font-bold tracking-tight">writethrough</p>
        <p class="text-gray-400 italic">Write broken German, get right German.</p>
    </div>

    <textarea
        wire:model.live.debounce.500ms="content"
        placeholder="Write here..."
        class="w-full text-lg leading-relaxed p-4 border border-gray-200 rounded-2xl h-full shadow-sm focus:outline-none"
    ></textarea>

    <div class="bg-white text-lg leading-relaxed whitespace-pre-wrap p-4 border border-gray-200 rounded-2xl shadow-sm"><p>{!!
        $this->response
    !!}</p></div>
</div>
