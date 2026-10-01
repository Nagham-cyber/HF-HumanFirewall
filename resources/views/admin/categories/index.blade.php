@extends('admin.layouts.app')

@section('title', 'Manage Categories')

@section('content')

<!-- Stats -->
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="card p-6 text-center">
        <span class="material-icons text-3xl text-[#4ADE80] mb-2">category</span>
        <p class="text-3xl font-black text-[#4ADE80]">{{ $stats['total'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Total Categories</p>
    </div>
    <div class="card p-6 text-center">
        <span class="material-icons text-3xl text-[#60A5FA] mb-2">menu_book</span>
        <p class="text-3xl font-black text-[#60A5FA]">{{ $stats['total_scenarios'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Scenarios</p>
    </div>
    <div class="card p-6 text-center">
        <span class="material-icons text-3xl text-orange-400 mb-2">assignment</span>
        <p class="text-3xl font-black text-orange-400">{{ $stats['total_tasks'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Cyber Tasks</p>
    </div>
</div>

<!-- Action Button -->
<div class="flex items-center justify-between mb-6">
    <h3 class="font-bold text-sm flex items-center gap-2">
        <span class="material-icons text-[#4ADE80]">folder</span>
        All Categories
    </h3>

    <a href="{{ route('admin.categories.create') }}" class="px-5 py-2 bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] rounded-full font-bold text-xs flex items-center gap-1">
        <span class="material-icons text-sm">add</span> New Category
    </a>
</div>

<!-- Categories List -->
<div class="space-y-2">
    @forelse($categories as $category)
    <div class="card p-4 flex items-center justify-between">
        <div class="flex items-center gap-4 flex-1 min-w-0">

            <!-- Icon -->
            <div class="w-12 h-12 rounded-xl bg-[#0A0A0F]/50 border border-[#4ADE80]/20 flex items-center justify-center shrink-0">
                <span class="material-icons {{ $category->color ?? 'text-[#4ADE80]' }}">{{ $category->icon }}</span>
            </div>

            <!-- Info -->
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2 flex-wrap">
                    <p class="font-bold text-sm">{{ $category->name }}</p>
                    <span class="text-[10px] font-mono text-[#94A3B8] px-2 py-0.5 rounded-full bg-[#0A0A0F]/50">{{ $category->slug }}</span>

                    @if(($category->type ?? 'both') == 'scenario')
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-[#4ADE80]/10 text-[#4ADE80]">SCENARIOS</span>
                    @elseif(($category->type ?? 'both') == 'task')
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-orange-500/10 text-orange-400">TASKS</span>
                    @else
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-[#60A5FA]/10 text-[#60A5FA]">BOTH</span>
                    @endif

                    @if(!($category->is_active ?? true))
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-red-500/10 text-red-400">INACTIVE</span>
                    @endif
                </div>
                <p class="text-[10px] text-[#94A3B8] mt-1 truncate">{{ $category->description ?? 'No description' }}</p>
            </div>

            <!-- Counts -->
            <div class="flex items-center gap-4 text-xs shrink-0">
                <div class="text-center">
                    <p class="font-black text-[#60A5FA]">{{ $category->scenarios_count }}</p>
                    <p class="text-[9px] text-[#94A3B8] uppercase">Scenarios</p>
                </div>
                <div class="text-center">
                    <p class="font-black text-orange-400">{{ $category->tasks_count }}</p>
                    <p class="text-[9px] text-[#94A3B8] uppercase">Tasks</p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-2 ml-4 shrink-0">
            <a href="{{ route('admin.categories.edit', $category->id) }}" class="p-2 rounded-lg hover:bg-[#4ADE80]/10 text-[#4ADE80]" title="Edit">
                <span class="material-icons text-lg">edit</span>
            </a>

            <form method="POST" action="{{ route('admin.categories.destroy', $category->id) }}" onsubmit="return confirm('Delete this category?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="p-2 rounded-lg hover:bg-red-500/10 text-red-400" title="Delete">
                    <span class="material-icons text-lg">delete</span>
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="card p-16 text-center">
        <span class="material-icons text-6xl text-[#94A3B8] mb-4">folder_open</span>
        <h3 class="text-xl font-black mb-2">No Categories</h3>
        <p class="text-[#94A3B8] text-sm mb-4">Create your first category to organize scenarios and tasks.</p>
        <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-2 px-5 py-2 bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] rounded-full font-bold text-xs">
            <span class="material-icons text-sm">add</span> Create First Category
        </a>
    </div>
    @endforelse
</div>

@endsection