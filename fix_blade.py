
import re
import os

file_path = r'D:\fundrize\resources\views\livewire\admin\whatsapp-template.blade.php'

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Add 'zakat' to $types array if not present
if "'zakat' => ['label' => 'Zakat'" not in content:
    content = re.sub(
        r"('tabungan_qurban' => \[.*?\],)",
        r"\1\n                'zakat' => ['label' => 'Zakat', 'icon' => 'fa-star-and-crescent', 'color' => 'amber'],",
        content
    )

# 2. Add zakat option to filterType select if not present
if '<option value="zakat">Zakat</option>' not in content:
    # This might be tricky if there are multiple selects.
    # The first one is filterType.
    content = re.sub(
        r'(<select wire:model\.live="filterType".*?>.*?<option value="tabungan_qurban">Tabungan Qurban</option>)',
        r'\1\n                <option value="zakat">Zakat</option>',
        content,
        flags=re.DOTALL
    )

# 3. Add zakat option to type select in modal
# Note: Task says wire:model.defer="type", but file has wire:model.live="type"
# I will handle both or just target the one that exists.
if 'wire:model.live="type"' in content:
    if '<option value="zakat">Zakat</option>' not in content: # This check is too broad, it's already there from step 2
         pass # Already added one, let's be more specific
    
    # Let's just find the second select and add it if missing there
    # Actually, I'll just search for the pattern in the modal area.
    modal_part = re.search(r'<!-- Create/Edit Modal -->.*?<!-- Content -->', content, re.DOTALL)
    if modal_part and '<option value="zakat">Zakat</option>' not in modal_part.group(0):
        content = re.sub(
            r'(<select wire:model\.(?:live|defer)="type".*?>.*?<option value="tabungan_qurban">Tabungan Qurban</option>)',
            r'\1\n                                            <option value="zakat">Zakat</option>',
            content,
            flags=re.DOTALL
        )

# 4. Remove disabled and (Segera Hadir)
content = content.replace(' disabled', '')
content = content.replace(' (Segera Hadir)', '')

# 5. Badge color for zakat
# It seems to be there already as a ternary.
# Task says "@elseif ($template->type === 'zakat') badge color bg-amber-100 text-amber-800"
# Current code uses nested ternaries:
# {{ $template->type === 'donasi'
#    ? 'bg-blue-100 text-blue-800'
#    : ($template->type === 'qurban'
#        ? 'bg-green-100 text-green-800'
#        : ($template->type === 'zakat'
#            ? 'bg-amber-100 text-amber-800'
#            : 'bg-purple-100 text-purple-800')) }}

# If the user explicitly wants @elseif, maybe I should refactor it?
# But the ternary is also valid.
# Let's see if I should change it to @if/@elseif for clarity.
# Actually, the user's "EXPECTED OUTCOME" says "Badge color bg-amber-100 text-amber-800".
# It IS there in the ternary.

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Edits completed.")
