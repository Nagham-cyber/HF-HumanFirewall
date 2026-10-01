@extends('admin.layouts.app')

@section('title', 'Edit Category')

@section('content')

<a href="{{ route('admin.categories.index') }}" class="text-[#4ADE80] text-sm mb-6 inline-flex items-center gap-2 hover:text-[#60A5FA]">
    <span class="material-icons text-sm">arrow_back</span> Back to Categories
</a>

<form method="POST" action="{{ route('admin.categories.update', $category->id) }}">
    @csrf

    <div class="card p-6 mb-6">
        <h3 class="font-bold text-sm text-[#4ADE80] uppercase mb-6 flex items-center gap-2">
            <span class="material-icons text-base">edit</span> Edit Category
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">Name *</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                    class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm focus:border-[#4ADE80]/50 focus:outline-none">
            </div>

            <div>
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">Slug *</label>
                <input type="text" name="slug" value="{{ old('slug', $category->slug) }}" required
                    class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm font-mono focus:border-[#4ADE80]/50 focus:outline-none">
            </div>

            <div>
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">Material Icon *</label>
                <input type="text" name="icon" value="{{ old('icon', $category->icon) }}" required
                    class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm focus:border-[#4ADE80]/50 focus:outline-none">
            </div>

            <div>
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">Color *</label>
                <input type="text" name="color" value="{{ old('color', $category->color ?? 'text-[#4ADE80]') }}" required
                    class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm font-mono focus:border-[#4ADE80]/50 focus:outline-none">
            </div>

            <div>
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">Category Type *</label>
                <select name="type" required class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm">
                    <option value="both" {{ ($category->type ?? 'both') == 'both' ? 'selected' : '' }}>Both</option>
                    <option value="scenario" {{ ($category->type ?? '') == 'scenario' ? 'selected' : '' }}>Scenarios Only</option>
                    <option value="task" {{ ($category->type ?? '') == 'task' ? 'selected' : '' }}>Cyber Tasks Only</option>
                </select>
            </div>

            <div>
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">Status</label>
                <label class="flex items-center gap-2 cursor-pointer mt-3">
                    <input type="checkbox" name="is_active" value="1" {{ ($category->is_active ?? true) ? 'checked' : '' }} class="accent-[#4ADE80] w-5 h-5">
                    <span class="text-sm">Active</span>
                </label>
            </div>

            <div class="md:col-span-2">
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">Description</label>
                <textarea name="description" rows="3"
                    class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm focus:border-[#4ADE80]/50 focus:outline-none">{{ old('description', $category->description) }}</textarea>
            </div>

        </div>
    </div>

    <div class="card p-6 flex items-center justify-between">
        <p class="text-xs text-[#94A3B8]">Changes apply immediately to all forms.</p>
        <div class="flex gap-3">
            <a href="{{ route('admin.categories.index') }}" class="px-6 py-3 rounded-full bg-[#0A0A0F] border border-[#4ADE80]/20 text-[#94A3B8] font-bold text-sm">Cancel</a>
            <button type="submit" class="px-8 py-3 rounded-full bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] font-black text-sm glow-green hover:scale-105 transition-all flex items-center gap-2">
                <span class="material-icons">save</span> Save Changes
            </button>
        </div>
    </div>

</form>

@endsection