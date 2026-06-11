<?php

namespace App\Http\Controllers;

use App\Imports\BlogImport;
use App\Models\Blog;
use App\Models\Category;
use App\Models\User;
use App\Models\Hsn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    // =========================================================================
    // AUTH
    // =========================================================================

    public function login()
    {
        return view('auth.login');
    }

    public function loginAttempt(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()->route('admin.index');
        }

        return redirect()->back()->withErrors(['email' => 'Invalid credentials.']);
    }

    // =========================================================================
    // DASHBOARD
    // =========================================================================

    public function index()
    {
        $totalBlogs      = Blog::count();
        $totalCategories = Category::count();
        return view('admin.index', compact('totalBlogs', 'totalCategories'));
    }

    // =========================================================================
    // CATEGORY
    // =========================================================================

    public function categories()
    {
        $categories = Category::orderBy('id', 'desc')->paginate(10);
        return view('admin.category', compact('categories'));
    }

    public function category_add()
    {
        $categories = Category::all();
        return view('admin.category-add', compact('categories'));
    }

    public function category_store(Request $request)
    {
        $request->validate([
            'name'             => 'required|min:3',
            'slug'             => 'required|unique:categories,slug',
            'image'            => 'nullable|mimes:png,jpg,jpeg,webp|max:2048',
            'meta_title'       => 'nullable|max:255|unique:categories,meta_title',
            'meta_keywords'    => 'nullable|max:255',
            'meta_description' => 'nullable',
        ]);

        $category = new Category();
        $category->parent_id = $request->parent_id;
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->meta_title = $request->meta_title;
        $category->meta_keywords = $request->meta_keywords;
        $category->meta_description = $request->meta_description;
        $category->image = '';

        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $file_name = time() . '.' . $image->getClientOriginalExtension();

            $destinationPath = public_path('uploads/categories');

            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            $image->move($destinationPath, $file_name);

            $category->image = $file_name;
        }

        $category->save();

        return redirect()->route('admin.categories')->with('status', 'Category added successfully');
    }

    public function category_edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.category-edit', compact('category'));
    }

    public function category_update(Request $request)
    {
        $request->validate([
            'name'             => 'required|min:3',
            'slug'             => 'required|string|max:255|unique:categories,slug,' . $request->id,
            'image'            => 'nullable|mimes:png,jpg,jpeg,webp|max:2048',
            'meta_title'       => 'nullable|unique:categories,meta_title,' . $request->id,
            'meta_keywords'    => 'nullable|max:255',
            'meta_description' => 'nullable',
        ]);

        $category = Category::findOrFail($request->id);

        $category->name = $request->name;
        $category->slug = Str::slug($request->slug ?? $request->name);
        $category->meta_title = $request->meta_title;
        $category->meta_keywords = $request->meta_keywords;
        $category->meta_description = $request->meta_description;

        if ($request->hasFile('image')) {

            if ($category->image) {
                $oldPath = public_path('uploads/categories/' . $category->image);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            $image = $request->file('image');
            $file_name = time() . '.' . $image->getClientOriginalExtension();

            $destinationPath = public_path('uploads/categories');

            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            $image->move($destinationPath, $file_name);

            $category->image = $file_name;
        }

        $category->save();

        return redirect()->route('admin.categories')->with('status', 'Category updated successfully');
    }

    public function category_delete($id)
    {
        $category = Category::findOrFail($id);

        if ($category->image) {
            $oldPath = public_path('uploads/categories/' . $category->image);
            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }
        }

        $category->delete();

        return redirect()->route('admin.categories')->with('status', 'Category deleted successfully');
    }

    // =========================================================================
    // BLOGS (DIRECT IMAGE UPLOAD ONLY)
    // =========================================================================

    public function blogs()
    {
        $blogs = Blog::orderBy('id', 'desc')->paginate(10);
        return view('admin.blogs', compact('blogs'));
    }

    public function blog_add()
    {
        $categories = Category::all();
        return view('admin.blog-add', compact('categories'));
    }

    public function blog_store(Request $request)
    {
        $request->validate([
            'title'             => 'required|string|max:255',
            'content'           => 'nullable|string',
            'image'             => 'required|image|mimes:jpg,jpeg,png,webp|max:2048|nullable',
            'category_id'       => 'required|exists:categories,id',
            'meta_title'        => 'nullable|string|max:255',
            'meta_keywords'     => 'nullable|string|max:255',
            'meta_description'  => 'nullable|string',
            'read_time'         => 'required|integer'
        ]);

        if ($request->hasFile('image')) {
            $image              = $request->file('image');
            $file_name          = time() . '.' . $image->getClientOriginalExtension();

            $destinationPath    = public_path('uploads/blogs');

            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            $image->move($destinationPath, $file_name);
        } else {
            $file_name = null;
        }

        $blog = Blog::create([
            'category_id'       => $request->category_id,
            'title'             => $request->title,
            'slug'              => Str::slug($request->title),
            'content'           => $request->content,
            'image'             => $file_name,
            'meta_title'        => $request->meta_title,
            'meta_keywords'     => $request->meta_keywords,
            'meta_description'  => $request->meta_description,
            'read_time'         => $request->read_time
        ]);

        return redirect()->route('admin.blogs')->with('status', 'Blog created successfully');
    }

    public function blog_edit($id)
    {
        $blog = Blog::findOrFail($id);
        $categories = Category::all();
        return view('admin.blog-edit', compact('blog', 'categories'));
    }

    public function blog_update(Request $request, Blog $blog)
    {
        $request->validate([
            'title'             => 'required|string|max:255',
            'content'           => 'nullable|string',
            'image'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'category_id'       => 'required|exists:categories,id',
            'meta_title'        => 'nullable|string|max:255',
            'meta_keywords'     => 'nullable|string|max:255',
            'meta_description'  => 'nullable|string',
            'read_time'         => 'required|integer'
        ]);

        $imageName = $blog->image;

        if ($request->hasFile('image')) {

            if ($blog->image) {
                $fullPath = public_path('uploads/blogs/' . $blog->image);
                if (File::exists($fullPath)) {
                    File::delete($fullPath);
                }
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            $destinationPath = public_path('uploads/blogs');

            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            $image->move($destinationPath, $imageName);
        }

        $blog->update([
            'title'             => $request->title,
            'slug'              => $blog->slug,
            'content'           => $request->content,
            'image'             => $imageName,
            'category_id'       => $request->category_id,
            'meta_title'        => $request->meta_title,
            'meta_keywords'     => $request->meta_keywords,
            'meta_description'  => $request->meta_description,
            'read_time'         => $request->read_time
        ]);

        return redirect()->route('admin.blogs')->with('success', 'Blog updated successfully');
    }

    public function BlogDelete($id)
    {
        $blog = Blog::findOrFail($id);

        if ($blog->image) {
            $fullPath = public_path('uploads/blogs/' . $blog->image);
            if (File::exists($fullPath)) {
                File::delete($fullPath);
            }
        }

        $blog->delete();

        return redirect()->route('admin.blogs')->with('status', 'Blog deleted successfully');
    }

    public function hsncode(){
        $hsnList    = Hsn::all();
        return view('admin.hsncode', compact('hsnList'));
    }

    public function addHSN(Request $request){
        if ($request->isMethod('get')) {
            return view('admin.addHSN');
        }

        if ($request->isMethod('post')) {
            $request->validate([
                'hsn_category'     => 'required|string|max:255',
                'hsn_code'         => 'required|string|max:100|unique:hsn,hsn_code',
                'hsn_description'  => 'required|string|max:255',
                'chapter'          => 'required|integer',
                'url'              => 'nullable|url|max:255',
            ]);

            HSN::create([
                'hsn_category'     => $request->hsn_category,
                'hsn_code'         => $request->hsn_code,
                'hsn_description'  => $request->hsn_description,
                'chapter'          => $request->chapter,
                'url'              => $request->url,
            ]);

            return redirect()->route('admin.hsncode')->with('success', 'HSN Code added successfully');
        }
    }

    public function editHSN(Request $request, $id){
        if($request->isMethod('get')) {
            $hsn = Hsn::findOrFail($id);
            return view('admin.editHSN', compact('hsn'));
        }

        $hsn = Hsn::findOrFail($id);

        $request->validate([
            'hsn_category'     => 'required|string|max:255',
            'hsn_code'         => 'required|string|max:100|unique:hsn,hsn_code,' . $id,
            'hsn_description'  => 'required|string|max:255',
            'chapter'          => 'required|integer',
            'url'              => 'nullable|url|max:255',
        ]);

        $hsn->update([
            'hsn_category'     => $request->hsn_category,
            'hsn_code'         => $request->hsn_code,
            'hsn_description'  => $request->hsn_description,
            'chapter'          => $request->chapter,
            'url'              => $request->url,
        ]);

        return redirect()->route('admin.hsncode')->with('success', 'HSN Code updated successfully');
    }

    public function hsnDelete( Request $request, $id){
        $hsn = Hsn::findOrFail($id);
        $hsn->delete();
        return redirect()->route('admin.hsncode')->with('success', 'HSN Code deleted successfully');
    }

    public function users()
    {
        $users = User::where('utype', '!=', 'ADM')->orderBy('id', 'desc')->paginate(10);
        return view('admin.users', compact('users'));
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }
}