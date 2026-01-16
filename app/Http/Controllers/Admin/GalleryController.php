<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryProject;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = GalleryProject::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('client', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('featured')) {
            $query->where('is_featured', $request->featured === 'yes');
        }

        $projects = $query->ordered()->paginate(20)->withQueryString();

        return view('admin.gallery.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.gallery.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:gallery_projects,slug',
            'description' => 'nullable|string',
            'client' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'completion_date' => 'nullable|date',
            'image' => 'nullable|image|max:5120',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|max:5120',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Generate slug
        $validated['slug'] = !empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['title']);

        // Ensure unique slug
        $baseSlug = $validated['slug'];
        $counter = 1;
        while (GalleryProject::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $baseSlug . '-' . $counter;
            $counter++;
        }

        // Handle booleans
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        // Handle main image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('gallery', 'public');
        }

        // Handle gallery images upload
        if ($request->hasFile('gallery_images')) {
            $galleryImages = [];
            foreach ($request->file('gallery_images') as $image) {
                $galleryImages[] = $image->store('gallery', 'public');
            }
            $validated['gallery_images'] = $galleryImages;
        }

        GalleryProject::create($validated);

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Project added to gallery successfully.');
    }

    public function edit(GalleryProject $gallery)
    {
        return view('admin.gallery.edit', compact('gallery'));
    }

    public function update(Request $request, GalleryProject $gallery)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:gallery_projects,slug,' . $gallery->id,
            'description' => 'nullable|string',
            'client' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'completion_date' => 'nullable|date',
            'image' => 'nullable|image|max:5120',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|max:5120',
            'remove_gallery_images' => 'nullable|array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Generate slug
        if (!empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['slug']);
        } else {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Ensure unique slug
        $baseSlug = $validated['slug'];
        $counter = 1;
        while (GalleryProject::where('slug', $validated['slug'])->where('id', '!=', $gallery->id)->exists()) {
            $validated['slug'] = $baseSlug . '-' . $counter;
            $counter++;
        }

        // Handle booleans
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        // Handle main image upload
        if ($request->hasFile('image')) {
            if ($gallery->image) {
                Storage::disk('public')->delete($gallery->image);
            }
            $validated['image'] = $request->file('image')->store('gallery', 'public');
        }

        // Handle removing gallery images
        $currentGalleryImages = $gallery->gallery_images ?? [];
        if ($request->filled('remove_gallery_images')) {
            foreach ($request->remove_gallery_images as $imageToRemove) {
                Storage::disk('public')->delete($imageToRemove);
                $currentGalleryImages = array_filter($currentGalleryImages, fn($img) => $img !== $imageToRemove);
            }
        }

        // Handle new gallery images upload
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $currentGalleryImages[] = $image->store('gallery', 'public');
            }
        }

        $validated['gallery_images'] = array_values($currentGalleryImages);

        // Remove the remove_gallery_images from validated as it's not a model field
        unset($validated['remove_gallery_images']);

        $gallery->update($validated);

        return redirect()->route('admin.gallery.edit', $gallery)
            ->with('success', 'Project updated successfully.');
    }

    public function destroy(GalleryProject $gallery)
    {
        // Delete main image
        if ($gallery->image) {
            Storage::disk('public')->delete($gallery->image);
        }

        // Delete gallery images
        if ($gallery->gallery_images) {
            foreach ($gallery->gallery_images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $gallery->delete();

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Project deleted from gallery successfully.');
    }

    public function toggleStatus(GalleryProject $gallery, string $field)
    {
        if (!in_array($field, ['is_active', 'is_featured'])) {
            return back()->with('error', 'Invalid field.');
        }

        $gallery->update([$field => !$gallery->$field]);
        $status = $gallery->$field ? 'enabled' : 'disabled';
        $fieldName = $field === 'is_active' ? 'Active status' : 'Featured status';

        return back()->with('success', "{$fieldName} {$status} successfully.");
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:gallery_projects,id',
        ]);

        foreach ($request->order as $index => $id) {
            GalleryProject::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
