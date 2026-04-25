<?php

use App\Enums\DocumentType;
use App\Enums\EmailStatus;
use App\Models\Client;
use App\Models\Driver;
use App\Models\EmailLog;
use App\Models\KpoDocument;
use App\Models\Pickup;
use App\Models\User;
use App\Models\WasteType;
use App\Services\KpoEmailService;
use App\Services\KpoPdfService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('local');

    $this->adminUser = User::factory()->create(['role' => 'admin']);
    $this->employeeUser = User::factory()->create(['role' => 'employee']);
    $this->driver = Driver::factory()->create();
    $this->driverUser = $this->driver->user;
    
    $this->client = Client::factory()->create([
        'email' => 'client@example.com',
        'company_name' => 'Test Company'
    ]);
    
    $this->wasteType = WasteType::factory()->create(['code' => '30 01 25']);
    
    $this->pickup = Pickup::factory()->create([
        'client_id' => $this->client->id,
        'waste_type_id' => $this->wasteType->id,
        'waste_quantity' => 150.5,
        'assigned_driver_id' => $this->driver->id
    ]);
    
    $this->kpoDocument = KpoDocument::factory()->create([
        'pickup_id' => $this->pickup->id,
        'client_id' => $this->client->id,
        'waste_code' => '30 01 25',
        'quantity' => 150.5,
        'kpo_number' => 'KPO-2026-00001',
        'pdf_path' => 'kpo_documents/test.pdf',
        'pdf_version' => 1,
        'is_emailed' => false
    ]);
});

describe('KPO Document Show Endpoint', function () {
    it('returns kpo document details successfully', function () {
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->getJson("/api/kpo-documents/{$this->kpoDocument->id}");
        
        expect($response->status())->toBe(200)
            ->and($response->json('success'))->toBeTrue()
            ->and($response->json('data'))->toBeArray();
    });

    it('includes pickup and client relationships', function () {
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->getJson("/api/kpo-documents/{$this->kpoDocument->id}");
        
        expect($response->json('data.pickup'))->toBeArray()
            ->and($response->json('data.client'))->toBeArray();
    });

    it('returns pdf metadata correctly', function () {
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->getJson("/api/kpo-documents/{$this->kpoDocument->id}");
        
        expect($response->json('data'))->toHaveKeys([
            'pdf_version',
            'pdf_exists',
            'needs_regeneration'
        ]);
    });
});

describe('KPO Document Index Endpoint', function () {
    it('lists all kpo documents with pagination', function () {
        KpoDocument::factory()->count(3)->create([
            'client_id' => $this->client->id
        ]);
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->getJson('/api/kpo-documents');
        
        expect($response->status())->toBe(200)
            ->and($response->json('success'))->toBeTrue()
            ->and($response->json('data'))->toBeArray()
            ->and($response->json('meta'))->toHaveKeys([
                'current_page',
                'last_page',
                'per_page',
                'total'
            ]);
    });

    it('filters documents by pickup_id', function () {
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->getJson("/api/kpo-documents?pickup_id={$this->pickup->id}");
        
        expect($response->status())->toBe(200)
            ->and($response->json('data'))->toBeArray();
    });

    it('filters documents by client_id', function () {
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->getJson("/api/kpo-documents?client_id={$this->client->id}");
        
        expect($response->status())->toBe(200)
            ->and($response->json('data'))->toBeArray();
    });

    it('filters documents by is_emailed status', function () {
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->getJson('/api/kpo-documents?is_emailed=false');
        
        expect($response->status())->toBe(200)
            ->and($response->json('data'))->toBeArray();
    });

    it('filters documents by date range', function () {
        $fromDate = now()->subDays(7)->format('Y-m-d');
        $toDate = now()->format('Y-m-d');
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->getJson("/api/kpo-documents?from_date={$fromDate}&to_date={$toDate}");
        
        expect($response->status())->toBe(200)
            ->and($response->json('data'))->toBeArray();
    });

    it('searches documents by kpo_number', function () {
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->getJson('/api/kpo-documents?search=KPO-2026');
        
        expect($response->status())->toBe(200)
            ->and($response->json('data'))->toBeArray();
    });

    it('respects per_page parameter', function () {
        KpoDocument::factory()->count(5)->create([
            'client_id' => $this->client->id
        ]);
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->getJson('/api/kpo-documents?per_page=3');
        
        expect($response->status())->toBe(200)
            ->and($response->json('meta.per_page'))->toBe(3);
    });
});

describe('Generate PDF for Pickup', function () {
    it('generates pdf for pickup successfully as admin', function () {
        $this->mock(KpoPdfService::class, function ($mock) {
            $mock->shouldReceive('generateKpoDocument')
                ->once()
                ->andReturn('kpo_documents/generated.pdf');
        });
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson("/api/kpo-documents/generate-for-pickup/{$this->pickup->id}");
        
        expect($response->status())->toBe(200)
            ->and($response->json('success'))->toBeTrue()
            ->and($response->json('message'))->toBe('PDF generated successfully');
    });

    it('generates pdf for pickup successfully as employee', function () {
        $this->mock(KpoPdfService::class, function ($mock) {
            $mock->shouldReceive('generateKpoDocument')
                ->once()
                ->andReturn('kpo_documents/generated.pdf');
        });
        
        $response = $this->actingAs($this->employeeUser, 'sanctum')
            ->postJson("/api/kpo-documents/generate-for-pickup/{$this->pickup->id}");
        
        expect($response->status())->toBe(200)
            ->and($response->json('success'))->toBeTrue();
    });

    it('generates pdf for assigned pickup as driver', function () {
        $this->mock(KpoPdfService::class, function ($mock) {
            $mock->shouldReceive('generateKpoDocument')
                ->once()
                ->andReturn('kpo_documents/generated.pdf');
        });
        
        $response = $this->actingAs($this->driverUser, 'sanctum')
            ->postJson("/api/kpo-documents/generate-for-pickup/{$this->pickup->id}");
        
        expect($response->status())->toBe(200)
            ->and($response->json('success'))->toBeTrue();
    });

    it('creates kpo document if not exists', function () {
        $newPickup = Pickup::factory()->create([
            'client_id' => $this->client->id,
            'waste_type_id' => $this->wasteType->id,
            'assigned_driver_id' => $this->driver->id
        ]);
        
        $this->mock(KpoPdfService::class, function ($mock) {
            $mock->shouldReceive('generateKpoDocument')
                ->once()
                ->andReturn('kpo_documents/generated.pdf');
        });
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson("/api/kpo-documents/generate-for-pickup/{$newPickup->id}");
        
        expect($response->status())->toBe(200)
            ->and($response->json('data.kpo_number'))->toBeString();
    });

    it('returns pdf metadata after generation', function () {
        $this->mock(KpoPdfService::class, function ($mock) {
            $mock->shouldReceive('generateKpoDocument')
                ->once()
                ->andReturn('kpo_documents/generated.pdf');
        });
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson("/api/kpo-documents/generate-for-pickup/{$this->pickup->id}");
        
        expect($response->json('data'))->toHaveKeys([
            'kpo_document_id',
            'kpo_number',
            'pdf_version',
            'path'
        ]);
    });
});

describe('Generate PDF for Driver\'s Pickup', function () {
    it('generates pdf for driver assigned pickup', function () {
        $this->mock(KpoPdfService::class, function ($mock) {
            $mock->shouldReceive('generateKpoDocument')
                ->once()
                ->andReturn('kpo_documents/generated.pdf');
        });
        
        $response = $this->actingAs($this->driverUser, 'sanctum')
            ->postJson('/api/kpo-documents/generate-my-pickup', [
                'pickup_id' => $this->pickup->id
            ]);
        
        expect($response->status())->toBe(200)
            ->and($response->json('success'))->toBeTrue()
            ->and($response->json('data.pickup'))->toBeArray();
    });

    it('validates pickup_id is required', function () {
        $response = $this->actingAs($this->driverUser, 'sanctum')
            ->postJson('/api/kpo-documents/generate-my-pickup', []);
        
        expect($response->status())->toBe(422);
    });

    it('validates pickup exists', function () {
        $response = $this->actingAs($this->driverUser, 'sanctum')
            ->postJson('/api/kpo-documents/generate-my-pickup', [
                'pickup_id' => 99999
            ]);
        
        expect($response->status())->toBe(422);
    });

    it('includes pickup details in response', function () {
        $this->mock(KpoPdfService::class, function ($mock) {
            $mock->shouldReceive('generateKpoDocument')
                ->once()
                ->andReturn('kpo_documents/generated.pdf');
        });
        
        $response = $this->actingAs($this->driverUser, 'sanctum')
            ->postJson('/api/kpo-documents/generate-my-pickup', [
                'pickup_id' => $this->pickup->id
            ]);
        
        expect($response->json('data.pickup'))->toHaveKeys([
            'id',
            'client_name',
            'scheduled_date'
        ]);
    });
});

describe('Generate or Regenerate PDF', function () {
    it('generates pdf for existing kpo document', function () {
        $this->mock(KpoPdfService::class, function ($mock) {
            $mock->shouldReceive('generateKpoDocument')
                ->once()
                ->andReturn('kpo_documents/regenerated.pdf');
        });
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson("/api/kpo-documents/{$this->kpoDocument->id}/generate-pdf");
        
        expect($response->status())->toBe(200)
            ->and($response->json('success'))->toBeTrue()
            ->and($response->json('message'))->toBe('PDF generated successfully');
    });

    it('returns updated pdf metadata', function () {
        $this->mock(KpoPdfService::class, function ($mock) {
            $mock->shouldReceive('generateKpoDocument')
                ->once()
                ->andReturn('kpo_documents/regenerated.pdf');
        });
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson("/api/kpo-documents/{$this->kpoDocument->id}/generate-pdf");
        
        expect($response->json('data'))->toHaveKeys([
            'kpo_document_id',
            'kpo_number',
            'pdf_version',
            'pdf_generated_at'
        ]);
    });

    it('increments pdf version on regeneration', function () {
        $this->mock(KpoPdfService::class, function ($mock) {
            $mock->shouldReceive('generateKpoDocument')
                ->once()
                ->andReturn('kpo_documents/regenerated.pdf');
        });
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson("/api/kpo-documents/{$this->kpoDocument->id}/generate-pdf");
        
        expect($response->status())->toBe(200)
            ->and($response->json('data.pdf_version'))->toBeInt();
    });
});

describe('Download PDF', function () {
    it('downloads pdf successfully', function () {
        Storage::put($this->kpoDocument->pdf_path, 'fake pdf content');
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->get("/api/kpo-documents/{$this->kpoDocument->id}/download");
        
        expect($response->status())->toBe(200);
    });

    it('returns 404 when pdf does not exist', function () {
        $kpo = KpoDocument::factory()->create([
            'client_id' => $this->client->id,
            'pdf_path' => null
        ]);
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->get("/api/kpo-documents/{$kpo->id}/download");
        
        expect($response->status())->toBe(404)
            ->and($response->json('success'))->toBeFalse();
    });

    it('sets correct content type for pdf', function () {
        Storage::put($this->kpoDocument->pdf_path, 'fake pdf content');
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->get("/api/kpo-documents/{$this->kpoDocument->id}/download");
        
        expect($response->headers->get('Content-Type'))->toContain('application/pdf');
    });
});

describe('Preview PDF', function () {
    it('previews pdf successfully', function () {
        Storage::put($this->kpoDocument->pdf_path, 'fake pdf content');
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->get("/api/kpo-documents/{$this->kpoDocument->id}/preview");
        
        expect($response->status())->toBe(200);
    });

    it('returns 404 when pdf does not exist', function () {
        $kpo = KpoDocument::factory()->create([
            'client_id' => $this->client->id,
            'pdf_path' => null
        ]);
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->get("/api/kpo-documents/{$kpo->id}/preview");
        
        expect($response->status())->toBe(404)
            ->and($response->json('success'))->toBeFalse();
    });

    it('sets inline content disposition', function () {
        Storage::put($this->kpoDocument->pdf_path, 'fake pdf content');
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->get("/api/kpo-documents/{$this->kpoDocument->id}/preview");
        
        expect($response->status())->toBe(200);
    });
});

describe('Email to Client', function () {
    it('sends email to client successfully', function () {
        $this->mock(KpoEmailService::class, function ($mock) {
            $mock->shouldReceive('sendToClient')
                ->once()
                ->andReturn(true);
        });
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson("/api/kpo-documents/{$this->kpoDocument->id}/email-to-client");
        
        expect($response->status())->toBe(200)
            ->and($response->json('success'))->toBeTrue()
            ->and($response->json('data.recipient_email'))->toBe('client@example.com');
    });

    it('sends email with custom message', function () {
        $this->mock(KpoEmailService::class, function ($mock) {
            $mock->shouldReceive('sendToClient')
                ->once()
                ->with(\Mockery::any(), 'Custom message here')
                ->andReturn(true);
        });
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson("/api/kpo-documents/{$this->kpoDocument->id}/email-to-client", [
                'custom_message' => 'Custom message here'
            ]);
        
        expect($response->status())->toBe(200)
            ->and($response->json('success'))->toBeTrue();
    });

    it('validates custom message max length', function () {
        $longMessage = str_repeat('a', 1001);
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson("/api/kpo-documents/{$this->kpoDocument->id}/email-to-client", [
                'custom_message' => $longMessage
            ]);
        
        expect($response->status())->toBe(422);
    });

    it('returns 400 when client has no email', function () {
        $clientWithoutEmail = Client::factory()->create(['email' => null]);
        $kpo = KpoDocument::factory()->create([
            'client_id' => $clientWithoutEmail->id
        ]);
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson("/api/kpo-documents/{$kpo->id}/email-to-client");
        
        expect($response->status())->toBe(400)
            ->and($response->json('success'))->toBeFalse();
    });

    it('returns sent timestamp in response', function () {
        $this->mock(KpoEmailService::class, function ($mock) {
            $mock->shouldReceive('sendToClient')
                ->once()
                ->andReturn(true);
        });
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson("/api/kpo-documents/{$this->kpoDocument->id}/email-to-client");
        
        expect($response->json('data.sent_at'))->toBeString();
    });
});

describe('Email to Custom Address', function () {
    it('sends email to custom address as admin', function () {
        $this->mock(KpoEmailService::class, function ($mock) {
            $mock->shouldReceive('sendToCustomEmail')
                ->once()
                ->andReturn(true);
        });
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson("/api/kpo-documents/{$this->kpoDocument->id}/email-to-custom", [
                'recipient_email' => 'custom@example.com',
                'authorization_reason' => 'Client requested alternative email'
            ]);
        
        expect($response->status())->toBe(200)
            ->and($response->json('success'))->toBeTrue()
            ->and($response->json('data.recipient_email'))->toBe('custom@example.com');
    });

    it('sends email to custom address as employee', function () {
        $this->mock(KpoEmailService::class, function ($mock) {
            $mock->shouldReceive('sendToCustomEmail')
                ->once()
                ->andReturn(true);
        });
        
        $response = $this->actingAs($this->employeeUser, 'sanctum')
            ->postJson("/api/kpo-documents/{$this->kpoDocument->id}/email-to-custom", [
                'recipient_email' => 'custom@example.com',
                'authorization_reason' => 'Client requested alternative email'
            ]);
        
        expect($response->status())->toBe(200)
            ->and($response->json('success'))->toBeTrue();
    });

    it('validates recipient_email is required', function () {
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson("/api/kpo-documents/{$this->kpoDocument->id}/email-to-custom", [
                'authorization_reason' => 'Test reason'
            ]);
        
        expect($response->status())->toBe(422);
    });

    it('validates recipient_email format', function () {
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson("/api/kpo-documents/{$this->kpoDocument->id}/email-to-custom", [
                'recipient_email' => 'invalid-email',
                'authorization_reason' => 'Test reason'
            ]);
        
        expect($response->status())->toBe(422);
    });

    it('validates authorization_reason is required', function () {
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson("/api/kpo-documents/{$this->kpoDocument->id}/email-to-custom", [
                'recipient_email' => 'custom@example.com'
            ]);
        
        expect($response->status())->toBe(422);
    });

    it('validates authorization_reason max length', function () {
        $longReason = str_repeat('a', 501);
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson("/api/kpo-documents/{$this->kpoDocument->id}/email-to-custom", [
                'recipient_email' => 'custom@example.com',
                'authorization_reason' => $longReason
            ]);
        
        expect($response->status())->toBe(422);
    });

    it('includes custom message when provided', function () {
        $this->mock(KpoEmailService::class, function ($mock) {
            $mock->shouldReceive('sendToCustomEmail')
                ->once()
                ->andReturn(true);
        });
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson("/api/kpo-documents/{$this->kpoDocument->id}/email-to-custom", [
                'recipient_email' => 'custom@example.com',
                'custom_message' => 'Special instructions',
                'authorization_reason' => 'Client requested'
            ]);
        
        expect($response->status())->toBe(200)
            ->and($response->json('success'))->toBeTrue();
    });

    it('includes authorized_by in response', function () {
        $this->mock(KpoEmailService::class, function ($mock) {
            $mock->shouldReceive('sendToCustomEmail')
                ->once()
                ->andReturn(true);
        });
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson("/api/kpo-documents/{$this->kpoDocument->id}/email-to-custom", [
                'recipient_email' => 'custom@example.com',
                'authorization_reason' => 'Client requested'
            ]);
        
        expect($response->json('data.authorized_by'))->toBeString();
    });
});

describe('Email History', function () {
    it('returns email history for kpo document', function () {
        EmailLog::factory()->create([
            'document_type' => DocumentType::KPO,
            'document_id' => $this->kpoDocument->id,
            'recipient_email' => 'test@example.com',
            'status' => EmailStatus::SENT
        ]);
        
        $this->mock(KpoEmailService::class, function ($mock) {
            $mock->shouldReceive('getEmailHistory')
                ->once()
                ->andReturn(collect([]));
        });
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->getJson("/api/kpo-documents/{$this->kpoDocument->id}/email-history");
        
        expect($response->status())->toBe(200)
            ->and($response->json('success'))->toBeTrue()
            ->and($response->json('data'))->toBeArray();
    });

    it('includes email status details', function () {
        $this->mock(KpoEmailService::class, function ($mock) {
            $emailLog = EmailLog::factory()->make([
                'status' => EmailStatus::SENT,
                'recipient_email' => 'test@example.com',
                'sent_at' => now()
            ]);
            
            $mock->shouldReceive('getEmailHistory')
                ->once()
                ->andReturn(collect([$emailLog]));
        });
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->getJson("/api/kpo-documents/{$this->kpoDocument->id}/email-history");
        
        expect($response->status())->toBe(200);
    });

    it('includes sender information when available', function () {
        $this->mock(KpoEmailService::class, function ($mock) {
            $mock->shouldReceive('getEmailHistory')
                ->once()
                ->andReturn(collect([]));
        });
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->getJson("/api/kpo-documents/{$this->kpoDocument->id}/email-history");
        
        expect($response->status())->toBe(200)
            ->and($response->json('data'))->toBeArray();
    });

    it('returns empty array when no email history', function () {
        $this->mock(KpoEmailService::class, function ($mock) {
            $mock->shouldReceive('getEmailHistory')
                ->once()
                ->andReturn(collect([]));
        });
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->getJson("/api/kpo-documents/{$this->kpoDocument->id}/email-history");
        
        expect($response->status())->toBe(200)
            ->and($response->json('data'))->toBeArray();
    });
});

describe('Retry Failed Email', function () {
    it('retries failed email successfully', function () {
        $emailLog = EmailLog::factory()->create([
            'document_type' => DocumentType::KPO,
            'document_id' => $this->kpoDocument->id,
            'status' => EmailStatus::FAILED,
            'recipient_email' => 'test@example.com'
        ]);
        
        $this->mock(KpoEmailService::class, function ($mock) {
            $mock->shouldReceive('retryEmail')
                ->once()
                ->andReturn(true);
        });
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson("/api/kpo-documents/email-logs/{$emailLog->id}/retry");
        
        expect($response->status())->toBe(200)
            ->and($response->json('success'))->toBeTrue()
            ->and($response->json('data.original_email_log_id'))->toBe($emailLog->id);
    });

    it('retries email with custom message', function () {
        $emailLog = EmailLog::factory()->create([
            'document_type' => DocumentType::KPO,
            'document_id' => $this->kpoDocument->id,
            'status' => EmailStatus::FAILED,
            'recipient_email' => 'test@example.com'
        ]);
        
        $this->mock(KpoEmailService::class, function ($mock) {
            $mock->shouldReceive('retryEmail')
                ->once()
                ->andReturn(true);
        });
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson("/api/kpo-documents/email-logs/{$emailLog->id}/retry", [
                'custom_message' => 'Retry with custom message'
            ]);
        
        expect($response->status())->toBe(200)
            ->and($response->json('success'))->toBeTrue();
    });

    it('validates custom message max length on retry', function () {
        $emailLog = EmailLog::factory()->create([
            'document_type' => DocumentType::KPO,
            'document_id' => $this->kpoDocument->id,
            'status' => EmailStatus::FAILED
        ]);
        
        $longMessage = str_repeat('a', 1001);
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson("/api/kpo-documents/email-logs/{$emailLog->id}/retry", [
                'custom_message' => $longMessage
            ]);
        
        expect($response->status())->toBe(422);
    });

    it('returns retried timestamp', function () {
        $emailLog = EmailLog::factory()->create([
            'document_type' => DocumentType::KPO,
            'document_id' => $this->kpoDocument->id,
            'status' => EmailStatus::FAILED
        ]);
        
        $this->mock(KpoEmailService::class, function ($mock) {
            $mock->shouldReceive('retryEmail')
                ->once()
                ->andReturn(true);
        });
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson("/api/kpo-documents/email-logs/{$emailLog->id}/retry");
        
        expect($response->json('data.retried_at'))->toBeString();
    });
});

describe('Email Statistics', function () {
    it('returns email statistics for kpo document', function () {
        $this->mock(KpoEmailService::class, function ($mock) {
            $mock->shouldReceive('getEmailStatistics')
                ->once()
                ->andReturn([
                    'total_sent' => 5,
                    'successful' => 4,
                    'failed' => 1,
                    'bounced' => 0,
                    'last_sent_at' => now(),
                    'unique_recipients' => 3
                ]);
        });
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->getJson("/api/kpo-documents/{$this->kpoDocument->id}/email-statistics");
        
        expect($response->status())->toBe(200)
            ->and($response->json('success'))->toBeTrue()
            ->and($response->json('data.statistics'))->toBeArray();
    });

    it('includes kpo number in statistics response', function () {
        $this->mock(KpoEmailService::class, function ($mock) {
            $mock->shouldReceive('getEmailStatistics')
                ->once()
                ->andReturn([
                    'total_sent' => 0,
                    'successful' => 0,
                    'failed' => 0,
                    'bounced' => 0,
                    'last_sent_at' => null,
                    'unique_recipients' => 0
                ]);
        });
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->getJson("/api/kpo-documents/{$this->kpoDocument->id}/email-statistics");
        
        expect($response->json('data.kpo_number'))->toBe($this->kpoDocument->kpo_number);
    });

    it('returns statistics with correct structure', function () {
        $this->mock(KpoEmailService::class, function ($mock) {
            $mock->shouldReceive('getEmailStatistics')
                ->once()
                ->andReturn([
                    'total_sent' => 3,
                    'successful' => 2,
                    'failed' => 1,
                    'bounced' => 0,
                    'last_sent_at' => now(),
                    'unique_recipients' => 2
                ]);
        });
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->getJson("/api/kpo-documents/{$this->kpoDocument->id}/email-statistics");
        
        expect($response->json('data.statistics'))->toHaveKeys([
            'total_sent',
            'successful',
            'failed',
            'bounced',
            'unique_recipients'
        ]);
    });
});

describe('Download PDF by Pickup', function () {
    it('downloads pdf by pickup as admin', function () {
        Storage::put($this->kpoDocument->pdf_path, 'fake pdf content');
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->get("/api/kpo-documents/pickup/{$this->pickup->id}/download");
        
        expect($response->status())->toBe(200);
    });

    it('downloads pdf by pickup as assigned driver', function () {
        Storage::put($this->kpoDocument->pdf_path, 'fake pdf content');
        
        $response = $this->actingAs($this->driverUser, 'sanctum')
            ->get("/api/kpo-documents/pickup/{$this->pickup->id}/download");
        
        expect($response->status())->toBe(200);
    });

    it('returns 404 when kpo document not found for pickup', function () {
        $pickupWithoutKpo = Pickup::factory()->create([
            'client_id' => $this->client->id,
            'assigned_driver_id' => $this->driver->id
        ]);
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->get("/api/kpo-documents/pickup/{$pickupWithoutKpo->id}/download");
        
        expect($response->status())->toBe(404)
            ->and($response->json('success'))->toBeFalse();
    });
});

describe('Preview PDF by Pickup', function () {
    it('previews pdf by pickup as admin', function () {
        Storage::put($this->kpoDocument->pdf_path, 'fake pdf content');
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->get("/api/kpo-documents/pickup/{$this->pickup->id}/preview");
        
        expect($response->status())->toBe(200);
    });

    it('previews pdf by pickup as assigned driver', function () {
        Storage::put($this->kpoDocument->pdf_path, 'fake pdf content');
        
        $response = $this->actingAs($this->driverUser, 'sanctum')
            ->get("/api/kpo-documents/pickup/{$this->pickup->id}/preview");
        
        expect($response->status())->toBe(200);
    });

    it('returns 404 when kpo document not found for pickup', function () {
        $pickupWithoutKpo = Pickup::factory()->create([
            'client_id' => $this->client->id,
            'assigned_driver_id' => $this->driver->id
        ]);
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->get("/api/kpo-documents/pickup/{$pickupWithoutKpo->id}/preview");
        
        expect($response->status())->toBe(404)
            ->and($response->json('success'))->toBeFalse();
    });
});

describe('KPO Number Generation', function () {
    it('generates unique kpo numbers for each document', function () {
        $this->mock(KpoPdfService::class, function ($mock) {
            $mock->shouldReceive('generateKpoDocument')
                ->andReturn('kpo_documents/generated.pdf');
        });
        
        $pickup1 = Pickup::factory()->create([
            'client_id' => $this->client->id,
            'waste_type_id' => $this->wasteType->id,
            'assigned_driver_id' => $this->driver->id
        ]);
        
        $pickup2 = Pickup::factory()->create([
            'client_id' => $this->client->id,
            'waste_type_id' => $this->wasteType->id,
            'assigned_driver_id' => $this->driver->id
        ]);
        
        $response1 = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson("/api/kpo-documents/generate-for-pickup/{$pickup1->id}");
        
        $response2 = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson("/api/kpo-documents/generate-for-pickup/{$pickup2->id}");
        
        expect($response1->json('data.kpo_number'))
            ->not->toBe($response2->json('data.kpo_number'));
    });

    it('generates kpo number with correct format', function () {
        $this->mock(KpoPdfService::class, function ($mock) {
            $mock->shouldReceive('generateKpoDocument')
                ->once()
                ->andReturn('kpo_documents/generated.pdf');
        });
        
        $pickup = Pickup::factory()->create([
            'client_id' => $this->client->id,
            'waste_type_id' => $this->wasteType->id,
            'assigned_driver_id' => $this->driver->id
        ]);
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson("/api/kpo-documents/generate-for-pickup/{$pickup->id}");
        
        expect($response->json('data.kpo_number'))
            ->toMatch('/^KPO-\d{4}-\d{5}$/');
    });

    it('includes current year in kpo number', function () {
        $this->mock(KpoPdfService::class, function ($mock) {
            $mock->shouldReceive('generateKpoDocument')
                ->once()
                ->andReturn('kpo_documents/generated.pdf');
        });
        
        $pickup = Pickup::factory()->create([
            'client_id' => $this->client->id,
            'waste_type_id' => $this->wasteType->id,
            'assigned_driver_id' => $this->driver->id
        ]);
        
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson("/api/kpo-documents/generate-for-pickup/{$pickup->id}");
        
        $kpoNumber = $response->json('data.kpo_number');
        expect($kpoNumber)->toContain((string) now()->year);
    });
});

describe('Authorization and Access Control', function () {
    it('requires authentication for all endpoints', function () {
        $response = $this->getJson("/api/kpo-documents/{$this->kpoDocument->id}");
        
        expect($response->status())->toBe(401);
    });

    it('allows admin to access all kpo documents', function () {
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->getJson("/api/kpo-documents/{$this->kpoDocument->id}");
        
        expect($response->status())->toBe(200);
    });

    it('allows employee to access all kpo documents', function () {
        $response = $this->actingAs($this->employeeUser, 'sanctum')
            ->getJson("/api/kpo-documents/{$this->kpoDocument->id}");
        
        expect($response->status())->toBe(200);
    });

    it('allows driver to access their assigned pickup kpo documents', function () {
        $response = $this->actingAs($this->driverUser, 'sanctum')
            ->getJson("/api/kpo-documents/{$this->kpoDocument->id}");
        
        expect($response->status())->toBe(200);
    });
});