<div class="menu-item" style="margin-left: {{ $level * 20 }}px; border: 1px solid #e0e0e0; border-radius: 6px; padding: 12px; margin-bottom: 8px; background: {{ $item->is_active ? '#fff' : '#f8f8f8' }};">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div style="display: flex; align-items: center; gap: 10px;">
            @if($item->icon)
                <i class="fas {{ $item->icon }}" style="color: #0079c1;"></i>
            @endif
            <div>
                <strong>{{ $item->title }}</strong>
                @if(!$item->is_active)
                    <span class="badge badge-secondary" style="margin-left: 5px;">Inactive</span>
                @endif
                <div style="font-size: 12px; color: #666;">
                    @if($item->type === 'page' && $item->page)
                        Page: {{ $item->page->title }}
                    @elseif($item->url)
                        {{ $item->url }}
                    @endif
                </div>
            </div>
        </div>
        <div class="actions">
            <button type="button" class="action-btn edit" title="Edit" onclick="openEditItemModal({{ $item->id }})">
                <i class="fas fa-edit"></i>
            </button>
            <button type="button" class="action-btn delete" title="Delete" onclick="deleteItem({{ $item->id }})">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>
</div>
@if($item->children->count() > 0)
    @foreach($item->children as $child)
        @include('admin.menus._item', ['item' => $child, 'level' => $level + 1])
    @endforeach
@endif
