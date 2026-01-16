<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $query = Page::withCount('sections');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            }
        }

        // Filter by template
        if ($request->filled('template')) {
            $query->where('template', $request->template);
        }

        $pages = $query->ordered()->paginate(20)->withQueryString();
        $templates = Page::getTemplates();

        return view('admin.pages.index', compact('pages', 'templates'));
    }

    public function create()
    {
        $templates = Page::getTemplates();
        $sectionTypes = PageSection::getTypes();
        return view('admin.pages.create', compact('templates', 'sectionTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'template' => 'required|string|in:' . implode(',', array_keys(Page::getTemplates())),
            'featured_image' => 'nullable|image|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'og_image' => 'nullable|image|max:2048',
            'is_published' => 'boolean',
            'show_in_header' => 'boolean',
            'show_in_footer' => 'boolean',
            'is_homepage' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'show_header' => 'boolean',
            'show_footer' => 'boolean',
            'show_breadcrumbs' => 'boolean',
            'layout_width' => 'nullable|string|in:container,full-width',
            'custom_css' => 'nullable|string',
            'custom_js' => 'nullable|string',
        ]);

        // Generate slug if not provided
        $validated['slug'] = !empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['title']);

        // Ensure unique slug
        $baseSlug = $validated['slug'];
        $counter = 1;
        while (Page::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $baseSlug . '-' . $counter;
            $counter++;
        }

        // Handle boolean defaults
        $validated['is_published'] = $request->boolean('is_published');
        $validated['show_in_header'] = $request->boolean('show_in_header');
        $validated['show_in_footer'] = $request->boolean('show_in_footer');
        $validated['is_homepage'] = $request->boolean('is_homepage');
        $validated['show_header'] = $request->boolean('show_header', true);
        $validated['show_footer'] = $request->boolean('show_footer', true);
        $validated['show_breadcrumbs'] = $request->boolean('show_breadcrumbs', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['layout_width'] = $validated['layout_width'] ?? 'container';

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('pages', 'public');
        }

        // Handle OG image upload
        if ($request->hasFile('og_image')) {
            $validated['og_image'] = $request->file('og_image')->store('pages/og', 'public');
        }

        $page = Page::create($validated);

        // If set as homepage, update other pages
        if ($page->is_homepage) {
            Page::where('id', '!=', $page->id)->update(['is_homepage' => false]);
        }

        return redirect()->route('admin.pages.edit', $page)
            ->with('success', 'Page created successfully. You can now add sections.');
    }

    public function edit(Page $page)
    {
        $templates = Page::getTemplates();
        $sectionTypes = PageSection::getTypes();
        $sections = $page->sections()->ordered()->get();

        return view('admin.pages.edit', compact('page', 'templates', 'sectionTypes', 'sections'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug,' . $page->id,
            'excerpt' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'template' => 'required|string|in:' . implode(',', array_keys(Page::getTemplates())),
            'featured_image' => 'nullable|image|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'og_image' => 'nullable|image|max:2048',
            'is_published' => 'boolean',
            'show_in_header' => 'boolean',
            'show_in_footer' => 'boolean',
            'is_homepage' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'show_header' => 'boolean',
            'show_footer' => 'boolean',
            'show_breadcrumbs' => 'boolean',
            'layout_width' => 'nullable|string|in:container,full-width',
            'custom_css' => 'nullable|string',
            'custom_js' => 'nullable|string',
        ]);

        // Generate slug if changed
        if (!empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['slug']);
        } else {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Ensure unique slug
        $baseSlug = $validated['slug'];
        $counter = 1;
        while (Page::where('slug', $validated['slug'])->where('id', '!=', $page->id)->exists()) {
            $validated['slug'] = $baseSlug . '-' . $counter;
            $counter++;
        }

        // Handle boolean defaults
        $validated['is_published'] = $request->boolean('is_published');
        $validated['show_in_header'] = $request->boolean('show_in_header');
        $validated['show_in_footer'] = $request->boolean('show_in_footer');
        $validated['is_homepage'] = $request->boolean('is_homepage');
        $validated['show_header'] = $request->boolean('show_header');
        $validated['show_footer'] = $request->boolean('show_footer');
        $validated['show_breadcrumbs'] = $request->boolean('show_breadcrumbs');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['layout_width'] = $validated['layout_width'] ?? 'container';

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($page->featured_image) {
                Storage::disk('public')->delete($page->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('pages', 'public');
        }

        // Handle OG image upload
        if ($request->hasFile('og_image')) {
            // Delete old image
            if ($page->og_image) {
                Storage::disk('public')->delete($page->og_image);
            }
            $validated['og_image'] = $request->file('og_image')->store('pages/og', 'public');
        }

        $page->update($validated);

        // If set as homepage, update other pages
        if ($page->is_homepage) {
            Page::where('id', '!=', $page->id)->update(['is_homepage' => false]);
        }

        return redirect()->route('admin.pages.edit', $page)
            ->with('success', 'Page updated successfully.');
    }

    public function destroy(Page $page)
    {
        // Delete associated images
        if ($page->featured_image) {
            Storage::disk('public')->delete($page->featured_image);
        }
        if ($page->og_image) {
            Storage::disk('public')->delete($page->og_image);
        }

        $page->delete();
        return redirect()->route('admin.pages.index')->with('success', 'Page deleted successfully.');
    }

    public function duplicate(Page $page)
    {
        $newPage = $page->replicate();
        $newPage->title = $page->title . ' (Copy)';
        $newPage->slug = $page->slug . '-copy-' . time();
        $newPage->is_published = false;
        $newPage->is_homepage = false;
        $newPage->save();

        // Duplicate sections
        foreach ($page->sections as $section) {
            $newSection = $section->replicate();
            $newSection->page_id = $newPage->id;
            $newSection->save();
        }

        return redirect()->route('admin.pages.edit', $newPage)
            ->with('success', 'Page duplicated successfully.');
    }

    public function togglePublish(Page $page)
    {
        $page->update(['is_published' => !$page->is_published]);
        $status = $page->is_published ? 'published' : 'unpublished';
        return back()->with('success', "Page {$status} successfully.");
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:pages,id',
        ]);

        foreach ($request->order as $index => $id) {
            Page::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    public function preview(Page $page)
    {
        return view('pages.preview', compact('page'));
    }

    // Section Management
    public function storeSection(Request $request, Page $page)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:' . implode(',', array_keys(PageSection::getTypes())),
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'settings' => 'nullable|array',
            'background_color' => 'nullable|string|max:20',
            'background_image' => 'nullable|image|max:2048',
            'text_color' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $validated['page_id'] = $page->id;
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $page->sections()->max('sort_order') + 1;

        // Handle background image upload
        if ($request->hasFile('background_image')) {
            $validated['background_image'] = $request->file('background_image')->store('sections', 'public');
        }

        $section = PageSection::create($validated);

        return redirect()->route('admin.pages.edit', $page)
            ->with('success', 'Section added successfully.');
    }

    public function updateSection(Request $request, Page $page, PageSection $section)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:' . implode(',', array_keys(PageSection::getTypes())),
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'settings' => 'nullable|array',
            'background_color' => 'nullable|string|max:20',
            'background_image' => 'nullable|image|max:2048',
            'text_color' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        // Handle background image upload
        if ($request->hasFile('background_image')) {
            if ($section->background_image) {
                Storage::disk('public')->delete($section->background_image);
            }
            $validated['background_image'] = $request->file('background_image')->store('sections', 'public');
        }

        $section->update($validated);

        return redirect()->route('admin.pages.edit', $page)
            ->with('success', 'Section updated successfully.');
    }

    public function destroySection(Page $page, PageSection $section)
    {
        if ($section->background_image) {
            Storage::disk('public')->delete($section->background_image);
        }

        $section->delete();
        return redirect()->route('admin.pages.edit', $page)
            ->with('success', 'Section deleted successfully.');
    }

    public function reorderSections(Request $request, Page $page)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:page_sections,id',
        ]);

        foreach ($request->order as $index => $id) {
            PageSection::where('id', $id)->where('page_id', $page->id)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    public function toggleSection(Page $page, PageSection $section)
    {
        $section->update(['is_active' => !$section->is_active]);
        return response()->json(['success' => true, 'is_active' => $section->is_active]);
    }
}
