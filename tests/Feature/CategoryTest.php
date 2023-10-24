<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_index_success(): void
    {
        $token = $this->registerAndLoginUser();

        $category = Category::factory()->create();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->get($this->apiBaseUrl . 'category');

        $response->assertStatus(200);

        $response = json_decode($response->content())[0];

        $this->assertEquals($response->slug, $category->slug);
        $this->assertEquals($response->name, $category->name);
        $this->assertEquals($response->model_arn, $category->model_arn);
    }

    public function test_category_show_success(): void
    {
        $token = $this->registerAndLoginUser();

        $category = Category::factory()->create();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->get($this->apiBaseUrl . 'category/' . $category->id);

        $response->assertStatus(200);

        $response = json_decode($response->content());

        $this->assertEquals($response->slug, $category->slug);
        $this->assertEquals($response->name, $category->name);
        $this->assertEquals($response->model_arn, $category->model_arn);
    }

    public function test_category_create_success(): void
    {
        $token = $this->registerAndLoginUser();

        $name = 'name';
        $slug = 'slug';
        $model_arn = 'model_arn';

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->post($this->apiBaseUrl . 'category', [
                'name' => $name,
                'slug' => $slug,
                'model_arn' => $model_arn,
            ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('categories', [
            'name' => $name,
            'slug' => $slug,
            'model_arn' => $model_arn,
        ]);
    }

    public function test_category_update_success(): void
    {
        $token = $this->registerAndLoginUser();

        $category = Category::factory()->create();

        $anotherName = 'another name';

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->put($this->apiBaseUrl . 'category/' . $category->id, [
                'id' => $category->id,
                'name' => $anotherName,
                'slug' => $category->slug,
                'model_arn' => $category->model_arn,
            ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => $anotherName,
            'slug' => $category->slug,
            'model_arn' => $category->model_arn,
        ]);
    }

    public function test_category_update_fail_not_found(): void
    {
        $token = $this->registerAndLoginUser();

        $anotherName = 'another name';

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->put($this->apiBaseUrl . 'category/' . 0, [
                'name' => $anotherName,
            ]);

        $response->assertStatus(404);
    }

    public function test_category_update_fail_required_parameter(): void
    {
        $token = $this->registerAndLoginUser();

        $category = Category::factory()->create();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->put($this->apiBaseUrl . 'category/' . $category->id, [
                'id' => $category->id,
                'slug' => $category->slug,
                'model_arn' => $category->model_arn,
            ]);

        $response->assertStatus(302);
    }

    public function test_category_delete_success(): void
    {
        $token = $this->registerAndLoginUser();

        $category = Category::factory()->create();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->delete($this->apiBaseUrl . 'category/' . $category->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
            'slug' => $category->slug,
            'model_arn' => $category->model_arn,
        ]);
    }

    public function test_category_delete_fail_not_found(): void
    {
        $token = $this->registerAndLoginUser();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->delete($this->apiBaseUrl . 'category/' . 0);

        $response->assertStatus(404);
    }
}
