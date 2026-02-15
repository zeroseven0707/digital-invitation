<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminTemplateController extends Controller
{
    public function index()
    {
        $templates = Template::withCount('invitations')->get();

        return view('admin.templates.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.templates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:templates,name',
            'description' => 'nullable|string',
            'html_file' => 'required|file|mimes:html,htm',
            'css_file' => 'required|file|mimes:css',
            'js_file' => 'required|file|mimes:js',
            'thumbnail' => 'required|file|mimes:svg,png,jpg,jpeg',
        ]);

        // Generate slug for directory name
        $slug = Str::slug($validated['name']);
        $templateDir = "templates/{$slug}";

        // Store files
        $htmlPath = $request->file('html_file')->storeAs($templateDir, 'template.html');
        $cssPath = $request->file('css_file')->storeAs($templateDir, 'style.css');
        $jsPath = $request->file('js_file')->storeAs($templateDir, 'script.js');

        // Handle thumbnail
        $thumbnailExtension = $request->file('thumbnail')->getClientOriginalExtension();
        $thumbnailPath = $request->file('thumbnail')->storeAs($templateDir, "thumbnail.{$thumbnailExtension}");

        // Create template record
        Template::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'thumbnail_path' => $thumbnailPath,
            'html_path' => $htmlPath,
            'css_path' => $cssPath,
            'js_path' => $jsPath,
            'is_active' => true,
        ]);

        return redirect()->route('admin.templates.index')
            ->with('success', 'Template created successfully.');
    }

    public function destroy($id)
    {
        $template = Template::findOrFail($id);

        // Check if template is being used
        if ($template->invitations()->count() > 0) {
            return redirect()->route('admin.templates.index')
                ->with('error', 'Cannot delete template that is being used by invitations.');
        }

        // Delete template files
        if ($template->html_path && Storage::exists($template->html_path)) {
            Storage::delete($template->html_path);
        }
        if ($template->css_path && Storage::exists($template->css_path)) {
            Storage::delete($template->css_path);
        }
        if ($template->js_path && Storage::exists($template->js_path)) {
            Storage::delete($template->js_path);
        }
        if ($template->thumbnail_path && Storage::exists($template->thumbnail_path)) {
            Storage::delete($template->thumbnail_path);
        }

        // Delete template directory if empty
        $templateDir = dirname($template->html_path);
        if (Storage::exists($templateDir) && count(Storage::files($templateDir)) === 0) {
            Storage::deleteDirectory($templateDir);
        }

        // Delete template record
        $template->delete();

        return redirect()->route('admin.templates.index')
            ->with('success', 'Template deleted successfully.');
    }
}
