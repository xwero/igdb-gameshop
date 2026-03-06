---
name: testing-pest
description: >-
  Tests applications using the Pest 4 PHP framework. Activates when writing tests, creating unit or feature
  tests, adding assertions, browser testing, debugging test failures,
  working with datasets or mocking; or when the user mentions test, spec, TDD, expects, assertion,
  coverage, or needs to verify functionality works.
---

# Pest 4 Testing

## Documentation

Use `search-docs` for detailed Pest 4 patterns and documentation.

## Basic Usage

### Test Organization

- Unit/Feature tests: `tests/Feature` and `tests/Unit` directories.
- Browser tests: `tests/Browser/` directory.
- Do NOT remove tests without approval - these are core application code.

### Creating tests

- Write happy path and edge case tests.

### Running Tests

- Run minimal tests with filter before finalizing: `php ./vendor/bin/pest --compact --filter=testName`.
- Run all tests: `php ./vendor/bin/pest --compact`.
- Run file: `php ./vendor/bin/pest --compact tests/Feature/ExampleTest.php`.

## Assertions

Use specific assertions (`assertSuccessful()`, `assertNotFound()`) instead of `assertStatus()`:

<code-snippet name="Pest Response Assertion" lang="php">

it('returns all', function () {
$this->postJson('/api/docs', [])->assertSuccessful();
});

</code-snippet>

| Use | Instead of |
|-----|------------|
| `assertSuccessful()` | `assertStatus(200)` |
| `assertNotFound()` | `assertStatus(404)` |
| `assertForbidden()` | `assertStatus(403)` |


## Datasets

Use datasets for repetitive tests (validation rules, etc.):

<code-snippet name="Pest Dataset Example" lang="php">

it('has emails', function (string $email) {
expect($email)->not->toBeEmpty();
})->with([
'james' => 'james@laravel.com',
'taylor' => 'taylor@laravel.com',
]);

</code-snippet>

## Pest 4 Features

| Feature | Purpose |
|---------|---------|
| Browser Testing | Full integration tests in real browsers |
| Smoke Testing | Validate multiple pages quickly |
| Visual Regression | Compare screenshots for visual changes |
| Test Sharding | Parallel CI runs |
| Architecture Testing | Enforce code conventions |

### Browser Test Example

Browser tests run in real browsers for full integration testing:

- Browser tests live in `tests/Browser/`.
- Interact with page: click, type, scroll, select, submit, drag-and-drop, touch gestures.
- Test on multiple browsers (Chrome, Firefox, Safari) if requested.

<code-snippet name="Pest Browser Test Example" lang="php">

it('may reset the password', function () {
Notification::fake();

    $this->actingAs(User::factory()->create());

    $page = visit('/sign-in');

    $page->assertSee('Sign In')
        ->assertNoJavaScriptErrors()
        ->click('Forgot Password?')
        ->fill('email', 'nuno@laravel.com')
        ->click('Send Reset Link')
        ->assertSee('We have emailed your password reset link!');

    Notification::assertSent(ResetPassword::class);
});

</code-snippet>

### Test Sharding

Split tests across parallel processes for faster CI runs.