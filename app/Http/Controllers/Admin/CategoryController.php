<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => Category::topLevel()->withCount('products')->with(['children' => fn ($query) => $query->withCount('products')])->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:categories,id'],
        ]);

        $this->assertValidParent($validated['parent_id'] ?? null);

        $category = new Category(['name' => $validated['name']]);
        $category->parent_id = $validated['parent_id'] ?? null;
        $category->slug = $this->uniqueSlug($validated['name']);
        $category->save();

        return back()->with('status', 'category-created');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:categories,id'],
        ]);

        $parentId = $validated['parent_id'] ?? null;

        abort_if($parentId == $category->id, 422, __('Une catégorie ne peut pas être sa propre sous-catégorie.'));

        if ($parentId !== null) {
            $this->assertValidParent($parentId);
            abort_if($category->children()->exists(), 422, __('Impossible : cette catégorie a déjà ses propres sous-catégories.'));
        }

        if ($validated['name'] !== $category->name) {
            $category->slug = $this->uniqueSlug($validated['name'], $category->id);
        }

        $category->name = $validated['name'];
        $category->parent_id = $parentId;
        $category->save();

        return back()->with('status', 'category-updated');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->exists() || Product::where('subcategory_id', $category->id)->exists()) {
            return back()->with('status', 'category-in-use');
        }

        if ($category->children()->exists()) {
            return back()->with('status', 'category-has-children');
        }

        $category->delete();

        return back()->with('status', 'category-deleted');
    }

    /**
     * A sub-category can only sit under a top-level category — never under another sub-category.
     */
    private function assertValidParent(?int $parentId): void
    {
        if ($parentId === null) {
            return;
        }

        abort_unless(Category::where('id', $parentId)->whereNull('parent_id')->exists(), 422, __('La catégorie parente sélectionnée est invalide.'));
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $suffix = 2;

        while (
            Category::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
