<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    /**
     * Get all available templates with search and pagination
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search', '');

        $query = Template::where('is_active', true);

        // Apply search filter
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $templates = $query->orderBy('name', 'asc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'templates' => $templates->items(),
            'pagination' => [
                'current_page' => $templates->currentPage(),
                'last_page' => $templates->lastPage(),
                'per_page' => $templates->perPage(),
                'total' => $templates->total(),
                'from' => $templates->firstItem(),
                'to' => $templates->lastItem(),
            ],
        ]);
    }

    /**
     * Get single template
     */
    public function show(Request $request, $id)
    {
        $template = Template::where('is_active', true)
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'template' => $template,
        ]);
    }
}
