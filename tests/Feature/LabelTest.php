<?php

namespace Tests\Feature;

use App\Models\Label;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LabelTest extends TestCase
{
    use RefreshDatabase;

    public function test_label_index_success(): void
    {
        $token = $this->registerAndLoginUser();

        $label = Label::factory()->create();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->get($this->apiBaseUrl . 'label');

        $response->assertStatus(200);

        $response = json_decode($response->content())[0];

        $this->assertEquals($response->slug, $label->slug);
        $this->assertEquals($response->name, $label->name);
        $this->assertEquals($response->type, $label->type);
        $this->assertEquals($response->validation_expression, $label->validation_expression);
    }

    public function test_label_show_success(): void
    {
        $token = $this->registerAndLoginUser();

        $label = Label::factory()->create();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->get($this->apiBaseUrl . 'label/' . $label->id);

        $response->assertStatus(200);

        $response = json_decode($response->content());

        $this->assertEquals($response->slug, $label->slug);
        $this->assertEquals($response->name, $label->name);
        $this->assertEquals($response->type, $label->type);
        $this->assertEquals($response->validation_expression, $label->validation_expression);
    }

    public function test_label_show_fail_not_found(): void
    {
        $token = $this->registerAndLoginUser();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->get($this->apiBaseUrl . 'label/' . 0);

        $response->assertStatus(404);
    }

    public function test_label_create_success(): void
    {
        $token = $this->registerAndLoginUser();

        $name = 'name';
        $slug = 'slug';
        $type = 'type';
        $validation_expression = 'validation_expression';

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->post($this->apiBaseUrl . 'label', [
                'name' => $name,
                'slug' => $slug,
                'type' => $type,
                'validation_expression' => $validation_expression
            ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('labels', [
            'name' => $name,
            'slug' => $slug,
            'type' => $type,
            'validation_expression' => $validation_expression
        ]);
    }

    public function test_label_create_fail_required_parameter(): void
    {
        $token = $this->registerAndLoginUser();

        $slug = 'slug';
        $type = 'type';
        $validation_expression = 'validation_expression';

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->post($this->apiBaseUrl . 'label', [
                'slug' => $slug,
                'type' => $type,
                'validation_expression' => $validation_expression
            ]);

        $response->assertStatus(302);

        $this->assertDatabaseMissing('labels', [
            'slug' => $slug,
            'type' => $type,
            'validation_expression' => $validation_expression
        ]);
    }

    public function test_label_update_success(): void
    {
        $token = $this->registerAndLoginUser();

        $label = Label::factory()->create();

        $anotherName = 'another name';

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->put($this->apiBaseUrl . 'label/' . $label->id, [
                'id' => $label->id,
                'name' => $anotherName,
                'slug' => $label->slug,
                'type' => $label->type,
                'validation_expression' => $label->validation_expression
            ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('labels', [
            'id' => $label->id,
            'name' => $anotherName,
            'slug' => $label->slug,
            'type' => $label->type,
            'validation_expression' => $label->validation_expression
        ]);
    }

    public function test_label_update_fail_required_parameter(): void
    {
        $token = $this->registerAndLoginUser();

        $label = Label::factory()->create();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->put($this->apiBaseUrl . 'label/' . $label->id, [
                'id' => $label->id,
                'slug' => $label->slug,
                'type' => $label->type,
                'validation_expression' => $label->validation_expression
            ]);

        $response->assertStatus(302);
    }

    public function test_label_update_fail_not_found(): void
    {
        $token = $this->registerAndLoginUser();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->put($this->apiBaseUrl . 'label/' . 0, [
                'slug' => 'slug',
                'type' => 'type',
                'validation_expression' => 'validation_expression'
            ]);

        $response->assertStatus(404);
    }

    public function test_label_delete_success(): void
    {
        $token = $this->registerAndLoginUser();
        $label = Label::factory()->create();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->delete($this->apiBaseUrl . 'label/' . $label->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('labels', [
            'id' => $label->id,
        ]);
    }

    public function test_label_delete_fail_not_found(): void
    {
        $token = $this->registerAndLoginUser();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->delete($this->apiBaseUrl . 'label/' . 0);

        $response->assertStatus(404);
    }
}
