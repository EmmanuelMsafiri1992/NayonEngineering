<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Widget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WidgetController extends Controller
{
    public function index(Request $request)
    {
        $query = Widget::query();

        // Filter by location
        if ($request->filled('location')) {
            $query->where('location', $request->location);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $widgets = $query->ordered()->get();
        $locations = Widget::getLocations();
        $types = Widget::getTypes();

        return view('admin.widgets.index', compact('widgets', 'locations', 'types'));
    }

    public function create()
    {
        $locations = Widget::getLocations();
        $types = Widget::getTypes();
        return view('admin.widgets.create', compact('locations', 'types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:' . implode(',', array_keys(Widget::getTypes())),
            'location' => 'required|string|in:' . implode(',', array_keys(Widget::getLocations())),
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'settings' => 'nullable|array',
            'background_color' => 'nullable|string|max:20',
            'background_image' => 'nullable|image|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        // Handle background image upload
        if ($request->hasFile('background_image')) {
            $validated['background_image'] = $request->file('background_image')->store('widgets', 'public');
        }

        Widget::create($validated);

        return redirect()->route('admin.widgets.index')
            ->with('success', 'Widget created successfully.');
    }

    public function edit(Widget $widget)
    {
        $locations = Widget::getLocations();
        $types = Widget::getTypes();
        return view('admin.widgets.edit', compact('widget', 'locations', 'types'));
    }

    public function update(Request $request, Widget $widget)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:' . implode(',', array_keys(Widget::getTypes())),
            'location' => 'required|string|in:' . implode(',', array_keys(Widget::getLocations())),
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'settings' => 'nullable|array',
            'background_color' => 'nullable|string|max:20',
            'background_image' => 'nullable|image|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        // Handle background image upload
        if ($request->hasFile('background_image')) {
            if ($widget->background_image) {
                Storage::disk('public')->delete($widget->background_image);
            }
            $validated['background_image'] = $request->file('background_image')->store('widgets', 'public');
        }

        $widget->update($validated);

        return redirect()->route('admin.widgets.index')
            ->with('success', 'Widget updated successfully.');
    }

    public function destroy(Widget $widget)
    {
        if ($widget->background_image) {
            Storage::disk('public')->delete($widget->background_image);
        }

        $widget->delete();
        return redirect()->route('admin.widgets.index')
            ->with('success', 'Widget deleted successfully.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:widgets,id',
        ]);

        foreach ($request->order as $index => $id) {
            Widget::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    public function toggle(Widget $widget)
    {
        $widget->update(['is_active' => !$widget->is_active]);
        return response()->json(['success' => true, 'is_active' => $widget->is_active]);
    }

    public function duplicate(Widget $widget)
    {
        $newWidget = $widget->replicate();
        $newWidget->name = $widget->name . ' (Copy)';
        $newWidget->is_active = false;
        $newWidget->save();

        return redirect()->route('admin.widgets.edit', $newWidget)
            ->with('success', 'Widget duplicated successfully.');
    }
}
