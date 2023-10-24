<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\File;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileTest extends TestCase
{
    use RefreshDatabase;

    public function test_file_index_success(): void
    {
        $token = $this->registerAndLoginUser();

        $file = File::factory()->create();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->get($this->apiBaseUrl . 'file');

        $response->assertStatus(200);

        $response = json_decode($response->content())[0];

        $this->assertEquals($response->id, $file->id);
        $this->assertEquals($response->name, $file->name);
        $this->assertEquals($response->status, $file->status);
    }

    public function test_file_show_success(): void
    {
        $token = $this->registerAndLoginUser();

        $file = File::factory()->create();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->get($this->apiBaseUrl . 'file/' . $file->id);

        $response->assertStatus(200);

        $response = json_decode($response->content());

        $this->assertEquals($response->id, $file->id);
        $this->assertEquals($response->name, $file->name);
        $this->assertEquals($response->status, $file->status);
    }

    public function test_file_show_fail_not_found(): void
    {
        $token = $this->registerAndLoginUser();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->get($this->apiBaseUrl . 'file/' . 0);

        $response->assertStatus(404);
    }

    public function test_file_download_success(): void
    {
        $token = $this->registerAndLoginUser();

        $file = File::factory()->create();

        $storage = Storage::fake('files');
        $storage->put($file->path, '');

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->get($this->apiBaseUrl . 'file/' . $file->id . '/download');

        $response->assertDownload();
    }

    public function test_file_download_fail_not_found(): void
    {
        $token = $this->registerAndLoginUser();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->get($this->apiBaseUrl . 'file/' . 0 . '/download');

        $response->assertStatus(404);
    }


    public function test_file_create_success(): void
    {
        $token = $this->registerAndLoginUser();
        $this->createFile($token);
    }

    public function test_file_update_success(): void
    {
        $token = $this->registerAndLoginUser();

        $file = File::factory()->create();

        $anotherVersion = 'another version';

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->put($this->apiBaseUrl . 'file/' . $file->id, [
                'version' => $anotherVersion,
                'category_id' => $file->category_id,
            ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('files', [
            'id' => $file->id,
            'version' => $anotherVersion,
            'category_id' => $file->category_id,
        ]);
    }

    public function test_file_update_fail_required_parameter(): void
    {
        $token = $this->registerAndLoginUser();

        $file = File::factory()->create();

        $anotherVersion = 'another version';

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->put($this->apiBaseUrl . 'file/' . $file->id, [
                'version' => $anotherVersion,
            ]);

        $response->assertStatus(302);

        $this->assertDatabaseMissing('files', [
            'id' => $file->id,
            'version' => $anotherVersion,
        ]);
    }

    public function test_file_delete_success(): void
    {
        $token = $this->registerAndLoginUser();
        $file = $this->createFile($token);

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->delete($this->apiBaseUrl . 'file/' . $file->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('files', [
            'id' => $file->id,
        ]);
    }

    public function test_file_delete_fail_not_found(): void
    {
        $token = $this->registerAndLoginUser();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->delete($this->apiBaseUrl . 'file/' . 1);

        $response->assertStatus(404);
    }

    public function createFile($token)
    {
        $category = Category::factory()->create();

        $version = 'version_test';
        $metadata = 'metadata_test';
        $filename = 'file_test.pdf';

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->post($this->apiBaseUrl . 'file', [
                'category_id' => $category->id,
                'version' => $version,
                'metadata' => $metadata,
                'file' => UploadedFile::fake()->create($filename, 400),
            ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('files', [
            'category_id' => $category->id,
            'version' => $version,
        ]);

        $file = File::where('category_id', $category->id)
            ->where('version', $version)
            ->first();

        Storage::disk('files')->assertExists($file->path);

        return $file;
    }
}
