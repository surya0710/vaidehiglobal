<?php

namespace Tests\Feature;

use App\Http\Middleware\StaticApiToken;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class ApiContentManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_static_token_is_required_for_write_apis(): void
    {
        $this->postJson('/api/categories/create', [
            'name' => 'Unauthorized Category',
        ])->assertUnauthorized();

        $this->withHeader('Authorization', 'Bearer wrong-token')
            ->postJson('/api/categories/create', [
                'name' => 'Unauthorized Category',
            ])
            ->assertUnauthorized();
    }

    public function test_category_can_be_created_and_updated_with_static_token(): void
    {
        $parent = Category::create([
            'name' => 'Parent Category',
            'slug' => 'parent-category',
            'status' => '1',
        ]);

        $createResponse = $this->authorizedJson('post', '/api/categories/create', [
            'name' => 'Steel Products',
            'slug' => 'steel-products',
            'parent_id' => $parent->id,
            'status' => '1',
            'meta_title' => 'Steel Products Meta',
            'meta_keywords' => 'steel,products',
            'meta_description' => 'Steel products description.',
        ]);

        $createResponse
            ->assertCreated()
            ->assertJsonPath('message', 'Category created successfully.')
            ->assertJsonPath('data.name', 'Steel Products')
            ->assertJsonPath('data.slug', 'steel-products');

        $categoryId = $createResponse->json('data.id');

        $this->assertDatabaseHas('categories', [
            'id' => $categoryId,
            'slug' => 'steel-products',
            'parent_id' => (string) $parent->id,
        ]);

        $this->authorizedJson('put', "/api/categories/{$categoryId}/modify", [
            'name' => 'Updated Steel Products',
            'slug' => 'updated-steel-products',
            'parent_id' => null,
            'status' => '0',
            'meta_title' => 'Updated Steel Products Meta',
            'meta_keywords' => 'updated,steel',
            'meta_description' => 'Updated steel products description.',
        ])
            ->assertOk()
            ->assertJsonPath('message', 'Category updated successfully.')
            ->assertJsonPath('data.name', 'Updated Steel Products')
            ->assertJsonPath('data.status', '0');

        $this->assertDatabaseHas('categories', [
            'id' => $categoryId,
            'name' => 'Updated Steel Products',
            'slug' => 'updated-steel-products',
            'status' => '0',
        ]);
    }

    public function test_blog_can_be_created_and_updated_with_static_token(): void
    {
        $category = Category::create([
            'name' => 'Blog Category',
            'slug' => 'blog-category',
            'status' => '1',
        ]);

        $createResponse = $this->authorizedRequest('post', '/api/blogs/create', [
            'category_slug' => 'blog-category',
            'title' => 'First API Blog',
            'slug' => 'first-api-blog',
            'content' => '<p>Initial content</p>',
            'status' => true,
            'featured_image' => UploadedFile::fake()->image('first-api-blog.png'),
            'meta_title' => 'First API Blog Meta',
            'meta_keywords' => 'api,blog',
            'meta_description' => 'First API blog description.',
            'read_time' => 4,
        ]);

        $createResponse
            ->assertCreated()
            ->assertJsonPath('message', 'Blog created successfully.')
            ->assertJsonPath('data.title', 'First API Blog')
            ->assertJsonPath('data.category.id', $category->id);

        $blogId = $createResponse->json('data.id');
        $blogImage = $createResponse->json('data.image');

        $this->assertDatabaseHas('blogs', [
            'id' => $blogId,
            'slug' => 'first-api-blog',
            'read_time' => 4,
        ]);
        $this->assertNotEmpty($blogImage);
        $this->assertFileExists(public_path('uploads/blogs/' . $blogImage));

        $newCategory = Category::create([
            'name' => 'Updated Blog Category',
            'slug' => 'updated-blog-category',
            'status' => '1',
        ]);

        $updateResponse = $this->authorizedRequest('post', "/api/blogs/{$blogId}/modify", [
            'category_slug' => 'updated-blog-category',
            'title' => 'Updated API Blog',
            'slug' => 'updated-api-blog',
            'content' => '<p>Updated content</p>',
            'status' => false,
            'featured_image' => UploadedFile::fake()->image('updated-api-blog.webp'),
            'meta_title' => 'Updated API Blog Meta',
            'meta_keywords' => 'updated,api,blog',
            'meta_description' => 'Updated API blog description.',
            'read_time' => 7,
        ]);

        $updateResponse
            ->assertOk()
            ->assertJsonPath('message', 'Blog updated successfully.')
            ->assertJsonPath('data.title', 'Updated API Blog')
            ->assertJsonPath('data.category.id', $newCategory->id);

        $updatedBlogImage = $updateResponse->json('data.image');

        $this->assertDatabaseHas('blogs', [
            'id' => $blogId,
            'category_id' => $newCategory->id,
            'title' => 'Updated API Blog',
            'slug' => 'updated-api-blog',
            'read_time' => 7,
        ]);
        $this->assertNotSame($blogImage, $updatedBlogImage);
        $this->assertFileDoesNotExist(public_path('uploads/blogs/' . $blogImage));
        $this->assertFileExists(public_path('uploads/blogs/' . $updatedBlogImage));

        $this->deleteUploadedTestFile('uploads/blogs', $updatedBlogImage);
    }

    public function test_category_and_blog_validation_errors_are_returned_as_json(): void
    {
        $category = Category::create([
            'name' => 'Existing Category',
            'slug' => 'existing-category',
            'status' => '1',
        ]);

        Blog::create([
            'category_id' => $category->id,
            'title' => 'Existing Blog',
            'slug' => 'existing-blog',
            'content' => 'Existing blog content.',
            'status' => true,
            'read_time' => 3,
        ]);

        $this->authorizedJson('post', '/api/categories/create', [
            'name' => 'No',
            'slug' => 'existing-category',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'slug']);

        $this->authorizedJson('post', '/api/blogs/create', [
            'category_slug' => 'missing-category',
            'title' => 'Existing Blog',
            'slug' => 'existing-blog',
            'read_time' => 0,
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['category_slug', 'slug', 'read_time', 'featured_image']);
    }

    private function authorizedJson(string $method, string $uri, array $data = [])
    {
        return $this->withHeader('Authorization', 'Bearer ' . StaticApiToken::TOKEN)
            ->json(strtoupper($method), $uri, $data);
    }

    private function authorizedRequest(string $method, string $uri, array $data = [])
    {
        return $this->withHeaders([
            'Authorization' => 'Bearer ' . StaticApiToken::TOKEN,
            'Accept' => 'application/json',
        ])->call(strtoupper($method), $uri, $data);
    }

    private function deleteUploadedTestFile(string $directory, ?string $fileName): void
    {
        if (! $fileName) {
            return;
        }

        $path = public_path($directory . '/' . $fileName);
        if (File::exists($path)) {
            File::delete($path);
        }
    }
}
