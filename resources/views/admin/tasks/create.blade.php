@extends('admin.layouts.app')

@section('title', 'Create Cyber Task')

@section('content')

<!-- Back -->
<a href="{{ route('admin.tasks.index') }}" class="text-[#4ADE80] text-sm mb-6 inline-flex items-center gap-2 hover:text-[#60A5FA]">
    <span class="material-icons text-sm">arrow_back</span> Back to Tasks
</a>

<form method="POST" action="{{ route('admin.tasks.store') }}" id="taskForm">
    @csrf

    <!-- Basic Info -->
    <div class="card p-6 mb-6">
        <h3 class="font-bold text-sm text-[#4ADE80] uppercase mb-4 flex items-center gap-2">
            <span class="material-icons text-base">info</span> Basic Information
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div>
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">Section *</label>
                <select name="section" id="sectionSelect" required
                    class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm focus:border-[#4ADE80]/50 focus:outline-none">
                    <option value="">-- Select Section --</option>
                    @foreach($sections as $key => $label)
                    <option value="{{ $key }}" {{ old('section') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">
                    Reference Code *
                    <span class="text-[10px] normal-case text-[#4ADE80] ml-2" id="codeStatus"></span>
                </label>
                <input type="text" name="reference_code" id="referenceCode" value="{{ old('reference_code') }}" required
                    placeholder="Will be generated automatically"
                    class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm font-mono focus:border-[#4ADE80]/50 focus:outline-none">
            </div>

            <div class="md:col-span-2">
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">Title *</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                    placeholder="e.g., Firewall Configuration & VLAN Segmentation"
                    class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm focus:border-[#4ADE80]/50 focus:outline-none">
            </div>

            <div class="md:col-span-2">
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">Short Description *</label>
                <textarea name="description" rows="2" required
                    placeholder="Brief description shown in the task card..."
                    class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm focus:border-[#4ADE80]/50 focus:outline-none">{{ old('description') }}</textarea>
            </div>

            <div class="md:col-span-2">
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">Overview</label>
                <textarea name="overview" rows="4"
                    placeholder="Detailed overview - why is this task important? What will the user learn?"
                    class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm focus:border-[#4ADE80]/50 focus:outline-none">{{ old('overview') }}</textarea>
            </div>

            <div>
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">Estimated Time *</label>
                <select name="estimated_time" required
                    class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm focus:border-[#4ADE80]/50 focus:outline-none">
                    <option value="30 minutes" {{ old('estimated_time') == '30 minutes' ? 'selected' : '' }}>30 minutes</option>
                    <option value="1 hour" {{ old('estimated_time') == '1 hour' ? 'selected' : '' }}>1 hour</option>
                    <option value="2 hours" {{ old('estimated_time', '2 hours') == '2 hours' ? 'selected' : '' }}>2 hours</option>
                    <option value="3 hours" {{ old('estimated_time') == '3 hours' ? 'selected' : '' }}>3 hours</option>
                    <option value="4 hours" {{ old('estimated_time') == '4 hours' ? 'selected' : '' }}>4 hours</option>
                    <option value="5 hours" {{ old('estimated_time') == '5 hours' ? 'selected' : '' }}>5 hours</option>
                    <option value="6 hours" {{ old('estimated_time') == '6 hours' ? 'selected' : '' }}>6 hours</option>
                    <option value="8 hours" {{ old('estimated_time') == '8 hours' ? 'selected' : '' }}>8 hours</option>
                    <option value="10 hours" {{ old('estimated_time') == '10 hours' ? 'selected' : '' }}>10 hours</option>
                </select>
            </div>

            <div>
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">Difficulty (1-5) *</label>
                <select name="difficulty" required
                    class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm focus:border-[#4ADE80]/50 focus:outline-none">
                    <option value="1" {{ old('difficulty') == '1' ? 'selected' : '' }}>1 - Very Easy</option>
                    <option value="2" {{ old('difficulty') == '2' ? 'selected' : '' }}>2 - Easy</option>
                    <option value="3" {{ old('difficulty', '3') == '3' ? 'selected' : '' }}>3 - Medium</option>
                    <option value="4" {{ old('difficulty') == '4' ? 'selected' : '' }}>4 - Hard</option>
                    <option value="5" {{ old('difficulty') == '5' ? 'selected' : '' }}>5 - Very Hard</option>
                </select>
            </div>

            <div>
                <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">XP Points *</label>
                <input type="number" name="points" value="{{ old('points', 100) }}" min="10" max="1000" required
                    class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm focus:border-[#4ADE80]/50 focus:outline-none">
                <p class="text-[10px] text-[#94A3B8] mt-1">Default: 100 XP</p>
            </div>

        </div>
    </div>

    <!-- Objectives -->
    <div class="card p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-sm text-[#60A5FA] uppercase flex items-center gap-2">
                <span class="material-icons text-base">flag</span> Learning Objectives
            </h3>
            <button type="button" onclick="addField('objectivesContainer', 'objective')" class="px-3 py-1 rounded-full bg-[#60A5FA]/10 text-[#60A5FA] text-xs font-bold hover:bg-[#60A5FA]/20">
                + Add Objective
            </button>
        </div>
        <div id="objectivesContainer" class="space-y-2">
            <div class="flex gap-2">
                <input type="text" name="objectives[]" placeholder="e.g., Understand VLAN concepts and their security benefits"
                    class="flex-1 bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-2 text-sm">
                <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 rounded-xl bg-red-500/10 text-red-400 text-xs">×</button>
            </div>
        </div>
    </div>

    <!-- Instructions -->
    <div class="card p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-sm text-[#4ADE80] uppercase flex items-center gap-2">
                <span class="material-icons text-base">list_alt</span> Step-by-Step Instructions
            </h3>
            <button type="button" onclick="addField('instructionsContainer', 'instruction')" class="px-3 py-1 rounded-full bg-[#4ADE80]/10 text-[#4ADE80] text-xs font-bold hover:bg-[#4ADE80]/20">
                + Add Step
            </button>
        </div>
        <div id="instructionsContainer" class="space-y-2">
            <div class="flex gap-2">
                <span class="w-8 h-10 flex items-center justify-center text-xs font-bold text-[#4ADE80]">1.</span>
                <input type="text" name="instructions[]" placeholder="e.g., Install VirtualBox on your machine"
                    class="flex-1 bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-2 text-sm">
                <button type="button" onclick="this.parentElement.remove(); renumber('instructionsContainer')" class="px-3 py-2 rounded-xl bg-red-500/10 text-red-400 text-xs">×</button>
            </div>
        </div>
    </div>

    <!-- Tools & Resources (Side by Side) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

        <!-- Tools -->
        <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-sm text-[#4ADE80] uppercase flex items-center gap-2">
                    <span class="material-icons text-base">build</span> Tools Required
                </h3>
                <button type="button" onclick="addField('toolsContainer', 'tool')" class="px-2 py-1 rounded-full bg-[#4ADE80]/10 text-[#4ADE80] text-xs font-bold hover:bg-[#4ADE80]/20">
                    + Add
                </button>
            </div>
            <div id="toolsContainer" class="space-y-2">
                <div class="flex gap-2">
                    <input type="text" name="tools_list[]" placeholder="e.g., VirtualBox - https://www.virtualbox.org"
                        class="flex-1 bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-2 text-xs">
                    <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 rounded-xl bg-red-500/10 text-red-400 text-xs">×</button>
                </div>
            </div>
        </div>

        <!-- Resources -->
        <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-sm text-[#60A5FA] uppercase flex items-center gap-2">
                    <span class="material-icons text-base">menu_book</span> Resources
                </h3>
                <button type="button" onclick="addField('resourcesContainer', 'resource')" class="px-2 py-1 rounded-full bg-[#60A5FA]/10 text-[#60A5FA] text-xs font-bold hover:bg-[#60A5FA]/20">
                    + Add
                </button>
            </div>
            <div id="resourcesContainer" class="space-y-2">
                <div class="flex gap-2">
                    <input type="text" name="resources[]" placeholder="e.g., pfSense Documentation - https://docs.netgate.com"
                        class="flex-1 bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-2 text-xs">
                    <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 rounded-xl bg-red-500/10 text-red-400 text-xs">×</button>
                </div>
            </div>
        </div>

    </div>

    <!-- Deliverables -->
    <div class="card p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-sm text-[#4ADE80] uppercase flex items-center gap-2">
                <span class="material-icons text-base">assignment_turned_in</span> Deliverables
            </h3>
            <button type="button" onclick="addField('deliverablesContainer', 'deliverable')" class="px-3 py-1 rounded-full bg-[#4ADE80]/10 text-[#4ADE80] text-xs font-bold hover:bg-[#4ADE80]/20">
                + Add Deliverable
            </button>
        </div>
        <div id="deliverablesContainer" class="space-y-2">
            <div class="flex gap-2">
                <input type="text" name="deliverables[]" placeholder="e.g., Screenshot of pfSense firewall rules"
                    class="flex-1 bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-2 text-sm">
                <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 rounded-xl bg-red-500/10 text-red-400 text-xs">×</button>
            </div>
        </div>
    </div>

    <!-- Hints -->
    <div class="card p-6 mb-6">
        <h3 class="font-bold text-sm text-yellow-400 uppercase mb-4 flex items-center gap-2">
            <span class="material-icons text-base">lightbulb</span> Pro Tip / Hint
        </h3>
        <textarea name="hints" rows="3"
            placeholder="e.g., If pfSense is too complex, try OPNsense which has a similar interface..."
            class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-3 text-sm focus:border-[#4ADE80]/50 focus:outline-none">{{ old('hints') }}</textarea>
    </div>

    <!-- Submit -->
    <div class="card p-6 flex items-center justify-between">
        <p class="text-xs text-[#94A3B8]">
            <span class="material-icons text-sm align-middle">info</span>
            The task will be immediately visible to all users.
        </p>
        <div class="flex gap-3">
            <a href="{{ route('admin.tasks.index') }}" class="px-6 py-3 rounded-full bg-[#0A0A0F] border border-[#4ADE80]/20 text-[#94A3B8] font-bold text-sm hover:text-[#4ADE80]">
                Cancel
            </a>
            <button type="submit" class="px-8 py-3 rounded-full bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] font-black text-sm glow-green hover:scale-105 transition-all flex items-center gap-2">
                <span class="material-icons">save</span> Create Task
            </button>
        </div>
    </div>

</form>

@endsection

@section('scripts')
<script>
    // توليد Reference Code تلقائياً
    document.getElementById('sectionSelect').addEventListener('change', function() {
        const section = this.value;
        if (!section) return;

        document.getElementById('codeStatus').textContent = 'Generating...';

        fetch('{{ route('admin.tasks.generateCode') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ section: section })
        })
        .then(r => r.json())
        .then(data => {
            document.getElementById('referenceCode').value = data.code;
            document.getElementById('codeStatus').textContent = '✓ Generated';
        })
        .catch(() => {
            document.getElementById('codeStatus').textContent = '';
        });
    });

    // إضافة حقل جديد
    function addField(containerId, type) {
        const container = document.getElementById(containerId);
        const div = document.createElement('div');
        div.className = 'flex gap-2';

        const placeholders = {
            'objective': 'e.g., Understand VLAN concepts',
            'instruction': 'e.g., Install VirtualBox',
            'tool': 'e.g., VirtualBox - https://...',
            'resource': 'e.g., Documentation - https://...',
            'deliverable': 'e.g., Screenshot of results'
        };

        div.innerHTML = `
            <input type="text" name="${containerId.replace('Container', '')}[]" placeholder="${placeholders[type] || ''}"
                class="flex-1 bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl px-4 py-2 text-sm">
            <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 rounded-xl bg-red-500/10 text-red-400 text-xs">×</button>
        `;
        container.appendChild(div);
    }

    // إعادة ترقيم التعليمات
    function renumber(containerId) {
        const container = document.getElementById(containerId);
        const rows = container.querySelectorAll('.flex.gap-2');
        rows.forEach((row, index) => {
            const span = row.querySelector('span');
            if (span) span.textContent = (index + 1) + '.';
        });
    }
</script>
@endsection