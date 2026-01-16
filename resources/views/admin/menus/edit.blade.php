@extends('admin.layouts.app')

@section('title', 'Edit Menu: ' . $menu->name)

@section('content')
    <div class="toolbar">
        <a href="{{ route('admin.menus.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back to Menus
        </a>
    </div>

    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3>Menu Details</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.menus.update', $menu) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label class="form-label">Menu Name *</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $menu->name) }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Location</label>
                            <select name="location" class="form-control">
                                <option value="">-- Select Location --</option>
                                @foreach($locations as $key => $name)
                                    <option value="{{ $key }}" {{ old('location', $menu->location) == $key ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-check">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $menu->is_active) ? 'checked' : '' }}>
                                <span>Active</span>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Menu
                        </button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Add Menu Item</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.menus.items.store', $menu) }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label class="form-label">Item Type *</label>
                            <select name="type" id="itemType" class="form-control" required onchange="toggleItemFields()">
                                @foreach($itemTypes as $key => $name)
                                    <option value="{{ $key }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Title *</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>

                        <div class="form-group" id="urlField">
                            <label class="form-label">URL</label>
                            <input type="text" name="url" class="form-control" placeholder="https://example.com or /path">
                        </div>

                        <div class="form-group" id="pageField" style="display: none;">
                            <label class="form-label">Select Page</label>
                            <select name="page_id" class="form-control">
                                <option value="">-- Select Page --</option>
                                @foreach($pages as $page)
                                    <option value="{{ $page->id }}">{{ $page->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Parent Item</label>
                            <select name="parent_id" class="form-control">
                                <option value="">-- None (Top Level) --</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="form-label">Icon (FontAwesome)</label>
                                    <input type="text" name="icon" class="form-control" placeholder="fa-home">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="form-label">Open In</label>
                                    <select name="target" class="form-control">
                                        <option value="_self">Same Window</option>
                                        <option value="_blank">New Window</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-check">
                                <input type="checkbox" name="is_active" value="1" checked>
                                <span>Active</span>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Item
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3>Menu Items</h3>
                </div>
                <div class="card-body">
                    @if($items->isEmpty())
                        <div class="empty-state" style="padding: 30px;">
                            <i class="fas fa-list"></i>
                            <p>No menu items yet</p>
                            <p style="font-size: 13px; color: #999;">Add items using the form on the left</p>
                        </div>
                    @else
                        <div id="menuItems">
                            @foreach($items as $item)
                                @include('admin.menus._item', ['item' => $item, 'level' => 0])
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Item Modal -->
    <div id="editItemModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 8px; max-width: 500px; width: 90%; max-height: 90vh; overflow-y: auto;">
            <div style="padding: 20px; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0;">Edit Menu Item</h3>
                <button type="button" onclick="closeEditItemModal()" style="background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
            </div>
            <form id="editItemForm" method="POST">
                @csrf
                @method('PUT')
                <div style="padding: 20px;">
                    <div class="form-group">
                        <label class="form-label">Item Type *</label>
                        <select name="type" id="edit_item_type" class="form-control" required onchange="toggleEditItemFields()">
                            @foreach($itemTypes as $key => $name)
                                <option value="{{ $key }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" id="edit_item_title" class="form-control" required>
                    </div>

                    <div class="form-group" id="edit_urlField">
                        <label class="form-label">URL</label>
                        <input type="text" name="url" id="edit_item_url" class="form-control">
                    </div>

                    <div class="form-group" id="edit_pageField" style="display: none;">
                        <label class="form-label">Select Page</label>
                        <select name="page_id" id="edit_item_page_id" class="form-control">
                            <option value="">-- Select Page --</option>
                            @foreach($pages as $page)
                                <option value="{{ $page->id }}">{{ $page->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Parent Item</label>
                        <select name="parent_id" id="edit_item_parent_id" class="form-control">
                            <option value="">-- None (Top Level) --</option>
                            @foreach($menu->allItems as $parentItem)
                                <option value="{{ $parentItem->id }}">{{ $parentItem->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Icon</label>
                                <input type="text" name="icon" id="edit_item_icon" class="form-control">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Open In</label>
                                <select name="target" id="edit_item_target" class="form-control">
                                    <option value="_self">Same Window</option>
                                    <option value="_blank">New Window</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-check">
                            <input type="checkbox" name="is_active" id="edit_item_is_active" value="1">
                            <span>Active</span>
                        </label>
                    </div>
                </div>
                <div style="padding: 20px; border-top: 1px solid #e0e0e0; text-align: right;">
                    <button type="button" class="btn btn-outline" onclick="closeEditItemModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Item</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Form -->
    <form id="deleteItemForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    // Menu items data
    const menuItemsData = @json($menu->allItems->keyBy('id'));

    function toggleItemFields() {
        const type = document.getElementById('itemType').value;
        document.getElementById('urlField').style.display = type === 'custom' ? 'block' : 'none';
        document.getElementById('pageField').style.display = type === 'page' ? 'block' : 'none';
    }

    function toggleEditItemFields() {
        const type = document.getElementById('edit_item_type').value;
        document.getElementById('edit_urlField').style.display = type === 'custom' ? 'block' : 'none';
        document.getElementById('edit_pageField').style.display = type === 'page' ? 'block' : 'none';
    }

    function openEditItemModal(itemId) {
        const item = menuItemsData[itemId];
        if (item) {
            document.getElementById('editItemForm').action = '{{ url("admin/menus/" . $menu->id . "/items") }}/' + itemId;
            document.getElementById('edit_item_type').value = item.type;
            document.getElementById('edit_item_title').value = item.title;
            document.getElementById('edit_item_url').value = item.url || '';
            document.getElementById('edit_item_page_id').value = item.page_id || '';
            document.getElementById('edit_item_parent_id').value = item.parent_id || '';
            document.getElementById('edit_item_icon').value = item.icon || '';
            document.getElementById('edit_item_target').value = item.target || '_self';
            document.getElementById('edit_item_is_active').checked = item.is_active;
            toggleEditItemFields();
            document.getElementById('editItemModal').style.display = 'flex';
        }
    }

    function closeEditItemModal() {
        document.getElementById('editItemModal').style.display = 'none';
    }

    function deleteItem(itemId) {
        if (confirm('Are you sure you want to delete this menu item?')) {
            const form = document.getElementById('deleteItemForm');
            form.action = '{{ url("admin/menus/" . $menu->id . "/items") }}/' + itemId;
            form.submit();
        }
    }

    // Close modal on outside click
    document.getElementById('editItemModal').addEventListener('click', function(e) {
        if (e.target === this) closeEditItemModal();
    });

    // Initialize
    toggleItemFields();
</script>
@endpush
