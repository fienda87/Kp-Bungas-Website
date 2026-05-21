<?php

use App\Models\News;
use App\Models\NewsletterSubscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can search news by keyword', function () {
    News::factory()->create([
        'title' => 'Important Community Meeting',
        'content' => 'We will discuss the future of our village.',
        'status' => 'published',
        'published_at' => now(),
    ]);

    News::factory()->create([
        'title' => 'New Park Opening',
        'content' => 'The new park is now open for public.',
        'status' => 'published',
        'published_at' => now(),
    ]);

    $response = $this->getJson('/api/news?search=Meeting');

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.title', 'Important Community Meeting');

    $response = $this->getJson('/api/news?search=park');

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.title', 'New Park Opening');
});

test('can filter news by multiple categories', function () {
    News::factory()->create(['category' => 'Event', 'status' => 'published', 'published_at' => now()]);
    News::factory()->create(['category' => 'Announcement', 'status' => 'published', 'published_at' => now()]);
    News::factory()->create(['category' => 'General', 'status' => 'published', 'published_at' => now()]);

    $response = $this->getJson('/api/news?category=Event,Announcement');

    $response->assertStatus(200)
        ->assertJsonCount(2, 'data');
});

test('increment news view count when shown', function () {
    $news = News::factory()->create(['status' => 'published', 'published_at' => now(), 'view_count' => 0]);

    $this->getJson("/api/news/{$news->slug}")->assertStatus(200);

    expect($news->fresh()->view_count)->toBe(1);

    $this->getJson("/api/news/{$news->slug}")->assertStatus(200);

    expect($news->fresh()->view_count)->toBe(2);
});

test('can subscribe to newsletter', function () {
    $response = $this->postJson('/api/subscribe', [
        'email' => 'test@example.com'
    ]);

    $response->assertStatus(201);
    $this->assertDatabaseHas('newsletter_subscribers', [
        'email' => 'test@example.com'
    ]);

    // Test duplicate email
    $response = $this->postJson('/api/subscribe', [
        'email' => 'test@example.com'
    ]);

    $response->assertStatus(422);
});
