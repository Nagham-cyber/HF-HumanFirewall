@extends('admin.layouts.app')

@section('title', 'Create Category')

@section('content')

<a href="{{ route('admin.categories.index') }}" class="text-[#4ADE80] text-sm mb-6 inline-flex items-center gap-2 hover:text-[#60A5FA]">
    <span class="material-icons text-sm">arrow_back</span> Back to Categories
</a>

<form method="POST" action="{{ route('admin.categories.store') }}">
    @csrf

    <div class="card p-6 mb-6">
        <h3 class="font-bold text-sm text-[#4ADE80] uppercase mb-6 flex items-center gap-2">
            <span class="material-icons text-base">add_circle</span> New Category
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">Name *</label>
                <input type="text" name="name" id="nameInput" value="{{ old('name') }}" required
                    placeholder="e.g., Network Security"
                    class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm focus:border-[#4ADE80]/50 focus:outline-none">
            </div>

            <div>
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">Slug *</label>
                <input type="text" name="slug" id="slugInput" value="{{ old('slug') }}" required
                    placeholder="network_security"
                    class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm font-mono focus:border-[#4ADE80]/50 focus:outline-none">
                <p class="text-[10px] text-[#94A3B8] mt-1">lowercase, numbers, _ or -</p>
            </div>

            <div>
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">Material Icon *</label>
                <input type="text" name="icon" id="iconInput" value="{{ old('icon', 'category') }}" required
                    placeholder="e.g., shield, lock, computer"
                    class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm focus:border-[#4ADE80]/50 focus:outline-none">
                <p class="text-[10px] text-[#94A3B8] mt-1">
                    <a href="https://fonts.google.com/icons" target="_blank" class="text-[#4ADE80] hover:text-[#60A5FA]">Browse icons →</a>
                </p>
            </div>

            <div>
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">Color *</label>
                <input type="text" name="color" id="colorInput" value="{{ old('color', 'text-[#4ADE80]') }}" required
                    placeholder="text-[#4ADE80]"
                    class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm font-mono focus:border-[#4ADE80]/50 focus:outline-none">
                <p class="text-[10px] text-[#94A3B8] mt-1">Tailwind color class</p>
            </div>

            <div>
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">Category Type *</label>
                <select name="type" required class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm focus:border-[#4ADE80]/50 focus:outline-none">
                    <option value="both" {{ old('type') == 'both' ? 'selected' : '' }}>Both (Scenarios + Tasks)</option>
                    <option value="scenario" {{ old('type') == 'scenario' ? 'selected' : '' }}>Scenarios Only</option>
                    <option value="task" {{ old('type') == 'task' ? 'selected' : '' }}>Cyber Tasks Only</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">Description</label>
                <textarea name="description" rows="3" placeholder="Brief description of this category..."
                    class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm focus:border-[#4ADE80]/50 focus:outline-none">{{ old('description') }}</textarea>
            </div>

        </div>
    </div>

    <!-- Preview -->
    <div class="card p-6 mb-6">
        <h3 class="font-bold text-sm text-[#60A5FA] uppercase mb-4 flex items-center gap-2">
            <span class="material-icons text-base">visibility</span> Preview
        </h3>

        <div class="flex items-center gap-4 p-4 bg-[#0A0A0F]/50 rounded-xl">
            <div class="w-12 h-12 rounded-xl bg-[#0A0A0F]/50 border border-[#4ADE80]/20 flex items-center justify-center">
                <span class="material-icons text-[#4ADE80]" id="previewIcon">category</span>
            </div>
            <div>
                <p class="font-bold text-sm" id="previewName">Category Name</p>
                <p class="text-[10px] text-[#94A3B8]" id="previewSlug">slug</p>
            </div>
        </div>
    </div>

    <!-- Submit -->
    <div class="card p-6 flex items-center justify-between">
        <p class="text-xs text-[#94A3B8]">The category will be available in scenario/task forms.</p>
        <div class="flex gap-3">
            <a href="{{ route('admin.categories.index') }}" class="px-6 py-3 rounded-full bg-[#0A0A0F] border border-[#4ADE80]/20 text-[#94A3B8] font-bold text-sm">Cancel</a>
            <button type="submit" class="px-8 py-3 rounded-full bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] font-black text-sm glow-green hover:scale-105 transition-all flex items-center gap-2">
                <span class="material-icons">save</span> Create Category
            </button>
        </div>
    </div>

</form>

@endsection

@section('scripts')
<script>
    const nameInput = document.getElementById('nameInput');
    const slugInput = document.getElementById('slugInput');
    const iconInput = document.getElementById('iconInput');

    nameInput.addEventListener('input', function() {
        const slug = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '_').replace(/^_+|_+$/g, '');
        slugInput.value = slug;
        document.getElementById('previewSlug').textContent = slug || 'slug';
        document.getElementById('previewName').textContent = this.value || 'Category Name';
    });

    iconInput.addEventListener('input', function() {
        document.getElementById('previewIcon').textContent = this.value || 'category';
    });
</script>
@endsection