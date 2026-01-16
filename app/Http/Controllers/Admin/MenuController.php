<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::withCount('allItems')->get();
        $locations = Menu::getLocations();
        return view('admin.menus.index', compact('menus', 'locations'));
    }

    public function create()
    {
        $locations = Menu::getLocations();
        return view('admin.menus.create', compact('locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|in:' . implode(',', array_keys(Menu::getLocations())),
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active', true);

        // Ensure unique slug
        $baseSlug = $validated['slug'];
        $counter = 1;
        while (Menu::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $baseSlug . '-' . $counter;
            $counter++;
        }

        $menu = Menu::create($validated);

        return redirect()->route('admin.menus.edit', $menu)
            ->with('success', 'Menu created successfully. You can now add items.');
    }

    public function edit(Menu $menu)
    {
        $locations = Menu::getLocations();
        $items = $menu->items()->with('children')->get();
        $pages = Page::published()->ordered()->get();
        $categories = Category::active()->ordered()->get();
        $itemTypes = MenuItem::getTypes();

        return view('admin.menus.edit', compact('menu', 'locations', 'items', 'pages', 'categories', 'itemTypes'));
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|in:' . implode(',', array_keys(Menu::getLocations())),
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');

        // Ensure unique slug
        $baseSlug = $validated['slug'];
        $counter = 1;
        while (Menu::where('slug', $validated['slug'])->where('id', '!=', $menu->id)->exists()) {
            $validated['slug'] = $baseSlug . '-' . $counter;
            $counter++;
        }

        $menu->update($validated);

        return redirect()->route('admin.menus.edit', $menu)
            ->with('success', 'Menu updated successfully.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('admin.menus.index')->with('success', 'Menu deleted successfully.');
    }

    // Menu Item Management
    public function storeItem(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:' . implode(',', array_keys(MenuItem::getTypes())),
            'url' => 'nullable|string|max:500',
            'page_id' => 'nullable|exists:pages,id',
            'parent_id' => 'nullable|exists:menu_items,id',
            'target' => 'nullable|string|in:_self,_blank',
            'icon' => 'nullable|string|max:100',
            'css_class' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $validated['menu_id'] = $menu->id;
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['target'] = $validated['target'] ?? '_self';
        $validated['sort_order'] = $menu->allItems()->max('sort_order') + 1;

        // Handle page type
        if ($validated['type'] === 'page' && !empty($validated['page_id'])) {
            $validated['url'] = null; // URL will be computed from page
        }

        MenuItem::create($validated);

        return redirect()->route('admin.menus.edit', $menu)
            ->with('success', 'Menu item added successfully.');
    }

    public function updateItem(Request $request, Menu $menu, MenuItem $item)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:' . implode(',', array_keys(MenuItem::getTypes())),
            'url' => 'nullable|string|max:500',
            'page_id' => 'nullable|exists:pages,id',
            'parent_id' => 'nullable|exists:menu_items,id',
            'target' => 'nullable|string|in:_self,_blank',
            'icon' => 'nullable|string|max:100',
            'css_class' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['target'] = $validated['target'] ?? '_self';

        // Prevent self-referencing parent
        if (!empty($validated['parent_id']) && $validated['parent_id'] == $item->id) {
            return back()->with('error', 'An item cannot be its own parent.');
        }

        $item->update($validated);

        return redirect()->route('admin.menus.edit', $menu)
            ->with('success', 'Menu item updated successfully.');
    }

    public function destroyItem(Menu $menu, MenuItem $item)
    {
        $item->delete();
        return redirect()->route('admin.menus.edit', $menu)
            ->with('success', 'Menu item deleted successfully.');
    }

    public function reorderItems(Request $request, Menu $menu)
    {
        $request->validate([
            'items' => 'required|array',
        ]);

        $this->updateItemOrder($request->items, null);

        return response()->json(['success' => true]);
    }

    private function updateItemOrder(array $items, $parentId)
    {
        foreach ($items as $index => $item) {
            MenuItem::where('id', $item['id'])->update([
                'sort_order' => $index,
                'parent_id' => $parentId,
            ]);

            if (!empty($item['children'])) {
                $this->updateItemOrder($item['children'], $item['id']);
            }
        }
    }

    public function toggleItem(Menu $menu, MenuItem $item)
    {
        $item->update(['is_active' => !$item->is_active]);
        return response()->json(['success' => true, 'is_active' => $item->is_active]);
    }
}
