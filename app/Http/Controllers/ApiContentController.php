<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ApiContentController extends Controller
{
    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('categories', 'slug')],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'status' => ['nullable', Rule::in(['0', '1', 0, 1])],
            'image' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'featured_image' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'meta_title' => ['nullable', 'string', 'max:255', Rule::unique('categories', 'meta_title')],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
        ]);

        $slug = Str::slug($data['slug'] ?? $data['name']);
        $this->ensureCategorySlugIsUnique($slug);

        $category = Category::create([
            'parent_id' => $data['parent_id'] ?? null,
            'name' => $data['name'],
            'slug' => $slug,
            'status' => (string) ($data['status'] ?? '1'),
            'image' => $this->storeUploadedImage($request, $this->featuredImageField($request), 'uploads/categories'),
            'meta_title' => $data['meta_title'] ?? null,
            'meta_keywords' => $data['meta_keywords'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
        ]);

        return response()->json([
            'message' => 'Category created successfully.',
            'data' => $category,
        ], 201);
    }

    public function updateCategory(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('categories', 'slug')->ignore($category->id)],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'status' => ['nullable', Rule::in(['0', '1', 0, 1])],
            'image' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'featured_image' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'meta_title' => ['nullable', 'string', 'max:255', Rule::unique('categories', 'meta_title')->ignore($category->id)],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
        ]);

        $slug = Str::slug($data['slug'] ?? $data['name']);
        $this->ensureCategorySlugIsUnique($slug, $category->id);

        $imageName = $category->image;
        $featuredImageField = $this->featuredImageField($request);
        if ($featuredImageField) {
            $this->deleteUploadedImage('uploads/categories', $category->image);
            $imageName = $this->storeUploadedImage($request, $featuredImageField, 'uploads/categories');
        }

        $category->update([
            'parent_id' => $data['parent_id'] ?? null,
            'name' => $data['name'],
            'slug' => $slug,
            'status' => (string) ($data['status'] ?? $category->status ?? '1'),
            'image' => $imageName,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_keywords' => $data['meta_keywords'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
        ]);

        return response()->json([
            'message' => 'Category updated successfully.',
            'data' => $category->fresh(),
        ]);
    }

    public function storeBlog(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['nullable', 'required_without:category_slug', 'exists:categories,id'],
            'category_slug' => ['nullable', 'required_without:category_id', 'string', 'exists:categories,slug'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('blogs', 'slug')],
            'content' => ['nullable', 'string'],
            'status' => ['nullable', 'boolean'],
            'image' => ['nullable', 'required_without:featured_image', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'featured_image' => ['nullable', 'required_without:image', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'read_time' => ['required', 'integer', 'min:1'],
        ]);

        $slug = Str::slug($data['slug'] ?? $data['title']);
        $this->ensureBlogSlugIsUnique($slug);
        $categoryId = $this->resolveBlogCategoryId($data);

        $blog = Blog::create([
            'category_id' => $categoryId,
            'title' => $data['title'],
            'slug' => $slug,
            'content' => $data['content'] ?? '',
            'status' => $data['status'] ?? true,
            'image' => $this->storeUploadedImage($request, $this->featuredImageField($request), 'uploads/blogs'),
            'meta_title' => $data['meta_title'] ?? null,
            'meta_keywords' => $data['meta_keywords'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'read_time' => $data['read_time'],
        ]);

        return response()->json([
            'message' => 'Blog created successfully.',
            'data' => $blog->load('category'),
        ], 201);
    }

    public function updateBlog(Request $request, Blog $blog)
    {
        $data = $request->validate([
            'category_id' => ['nullable', 'required_without:category_slug', 'exists:categories,id'],
            'category_slug' => ['nullable', 'required_without:category_id', 'string', 'exists:categories,slug'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('blogs', 'slug')->ignore($blog->id)],
            'content' => ['nullable', 'string'],
            'status' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'featured_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'read_time' => ['required', 'integer', 'min:1'],
        ]);

        $slug = isset($data['slug']) ? Str::slug($data['slug']) : $blog->slug;
        $this->ensureBlogSlugIsUnique($slug, $blog->id);
        $categoryId = $this->resolveBlogCategoryId($data);

        $imageName = $blog->image;
        $featuredImageField = $this->featuredImageField($request);
        if ($featuredImageField) {
            $this->deleteUploadedImage('uploads/blogs', $blog->image);
            $imageName = $this->storeUploadedImage($request, $featuredImageField, 'uploads/blogs');
        }

        $blog->update([
            'category_id' => $categoryId,
            'title' => $data['title'],
            'slug' => $slug,
            'content' => $data['content'] ?? '',
            'status' => $data['status'] ?? $blog->status,
            'image' => $imageName,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_keywords' => $data['meta_keywords'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'read_time' => $data['read_time'],
        ]);

        return response()->json([
            'message' => 'Blog updated successfully.',
            'data' => $blog->fresh()->load('category'),
        ]);
    }

    private function storeUploadedImage(Request $request, ?string $field, string $directory): ?string
    {
        if (! $field || ! $request->hasFile($field)) {
            return null;
        }

        $image = $request->file($field);
        $fileName = time() . '_' . Str::random(8) . '.' . $image->getClientOriginalExtension();
        $destinationPath = public_path($directory);

        if (! File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        $image->move($destinationPath, $fileName);

        return $fileName;
    }

    private function featuredImageField(Request $request): ?string
    {
        if ($request->hasFile('featured_image')) {
            return 'featured_image';
        }

        if ($request->hasFile('image')) {
            return 'image';
        }

        return null;
    }

    private function resolveBlogCategoryId(array $data): int
    {
        if (! empty($data['category_slug'])) {
            return Category::where('slug', $data['category_slug'])->value('id');
        }

        return (int) $data['category_id'];
    }

    private function deleteUploadedImage(string $directory, ?string $fileName): void
    {
        if (! $fileName) {
            return;
        }

        $path = public_path($directory . '/' . $fileName);
        if (File::exists($path)) {
            File::delete($path);
        }
    }

    private function ensureCategorySlugIsUnique(string $slug, ?int $ignoreId = null): void
    {
        $query = Category::where('slug', $slug);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'slug' => ['The slug has already been taken.'],
            ]);
        }
    }

    private function ensureBlogSlugIsUnique(string $slug, ?int $ignoreId = null): void
    {
        $query = Blog::where('slug', $slug);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'slug' => ['The slug has already been taken.'],
            ]);
        }
    }
}
