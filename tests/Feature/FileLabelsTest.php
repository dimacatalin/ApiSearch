<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\File;
use App\Models\FileLabel;
use App\Models\Label;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileLabelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_file_label_index_success(): void
    {
        $token = $this->registerAndLoginUser();

        $fileLabel = FileLabel::factory()->create();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->get($this->apiBaseUrl . 'file-label');

        $response->assertStatus(200);

        $response = json_decode($response->content())[0];

        $this->assertEquals($response->id, $fileLabel->id);
        $this->assertEquals($response->label_id, $fileLabel->label_id);
        $this->assertEquals($response->label->id, $fileLabel->label_id);
        $this->assertEquals($response->file_id, $fileLabel->file_id);
        $this->assertEquals($response->file->id, $fileLabel->file_id);
        $this->assertEquals($response->coordinates, $fileLabel->coordinates);
        $this->assertEquals($response->page_number, $fileLabel->page_number);
    }

    public function test_file_label_show_success(): void
    {
        $token = $this->registerAndLoginUser();

        $fileLabel = FileLabel::factory()->create();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->get($this->apiBaseUrl . 'file-label/' . $fileLabel->id);

        $response->assertStatus(200);

        $response = json_decode($response->content());

        $this->assertEquals($response->id, $fileLabel->id);
        $this->assertEquals($response->label_id, $fileLabel->label_id);
        $this->assertEquals($response->label->id, $fileLabel->label_id);
        $this->assertEquals($response->file_id, $fileLabel->file_id);
        $this->assertEquals($response->file->id, $fileLabel->file_id);
        $this->assertEquals($response->coordinates, $fileLabel->coordinates);
        $this->assertEquals($response->page_number, $fileLabel->page_number);
    }

    public function test_file_label_show_fail_not_found(): void
    {
        $token = $this->registerAndLoginUser();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->get($this->apiBaseUrl . 'file-label/' . 0);

        $response->assertStatus(404);
    }

    public function test_file_label_create_success(): void
    {
        $token = $this->registerAndLoginUser();

        $file = File::factory()->create();
        $label = Label::factory()->create();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->post($this->apiBaseUrl . 'file-label', [
                'file_id' => $file->id,
                'label_id' => $label->id,
                'coordinates' => '1234.23, 1241.34',
                'page_number' => 2,
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('labels_to_files', [
            'file_id' => $file->id,
            'label_id' => $label->id,
        ]);
    }

    public function test_file_label_update_success(): void
    {
        $token = $this->registerAndLoginUser();

        $fileLabel = FileLabel::factory()->create();

        $anotherPageNumber = 3;
        $otherCoordinates = '234523.32, 2352.45';

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->put($this->apiBaseUrl . 'file-label/' . $fileLabel->id, [
                'label_id' => $fileLabel->label_id,
                'file_id' => $fileLabel->file_id,
                'coordinates' => $otherCoordinates,
                'page_number' => $anotherPageNumber,
            ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('labels_to_files', [
            'id' => $fileLabel->id,
            'label_id' => $fileLabel->label_id,
            'file_id' => $fileLabel->file_id,
            'coordinates' => $otherCoordinates,
            'page_number' => $anotherPageNumber,
        ]);
    }

    public function test_file_label_update_fail_required_parameter(): void
    {
        $token = $this->registerAndLoginUser();

        $fileLabel = FileLabel::factory()->create();

        $anotherPageNumber = 3;
        $otherCoordinates = '234523.32, 2352.45';

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->put($this->apiBaseUrl . 'file-label/' . $fileLabel->id, [
                'label_id' => $fileLabel->label_id,
                'coordinates' => $otherCoordinates,
                'page_number' => $anotherPageNumber,
            ]);

        $response->assertStatus(302);

        $this->assertDatabaseMissing('labels_to_files', [
            'id' => $fileLabel->id,
            'label_id' => $fileLabel->label_id,
            'coordinates' => $otherCoordinates,
            'page_number' => $anotherPageNumber,
        ]);
    }

    public function test_file_label_delete_success(): void
    {
        $token = $this->registerAndLoginUser();
        $fileLabel = FileLabel::factory()->create();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->delete($this->apiBaseUrl . 'file-label/' . $fileLabel->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('labels_to_files', [
            'id' => $fileLabel->id,
        ]);
    }

    public function test_file_label_delete_fail_not_found(): void
    {
        $token = $this->registerAndLoginUser();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->delete($this->apiBaseUrl . 'file-label/' . 1);

        $response->assertStatus(404);
    }
}
