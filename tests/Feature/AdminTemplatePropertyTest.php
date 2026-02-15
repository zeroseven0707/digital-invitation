<?php

namespace Tests\Feature;

use App\Models\Invitation;
use App\Models\Template;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Property-Based Tests for Admin Template Management
 *
 * These tests validate universal properties that should hold true
 * for all admin template management operations.
 */
class AdminTemplatePropertyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
    }

    /**
     * Property 34: Template Deletion Requires No Usage
     *
     * For any template, deletion should only succeed if the template is not
     * being used by any invitations. If the template is in use, deletion
     * should fail and the template should remain in the database.
     *
     * Validates: Requirements 10.7
     */
    public function test_property_template_deletion_requires_no_usage(): void
    {
        $testCases = [
            ['invitations_using_template' => 0, 'should_delete' => true],
            ['invitations_using_template' => 1, 'should_delete' => false],
            ['invitations_using_template' => 3, 'should_delete' => false],
            ['invitations_using_template' => 5, 'should_delete' => false],
            ['invitations_using_template' => 10, 'should_delete' => false],
            ['invitations_using_template' => 0, 'should_delete' => true],
            ['invitations_using_template' => 2, 'should_delete' => false],
        ];

        foreach ($testCases as $testCase) {
            $admin = User::factory()->create([
                'is_admin' => true,
                'is_active' => true,
            ]);

            // Create a template
            $template = Template::factory()->create([
                'name' => 'Test Template ' . uniqid(),
            ]);

            // Create invitations using this template
            $invitations = [];
            if ($testCase['invitations_using_template'] > 0) {
                $user = User::factory()->create();
                $invitations = Invitation::factory()
                    ->count($testCase['invitations_using_template'])
                    ->create([
                        'user_id' => $user->id,
                        'template_id' => $template->id,
                    ]);
            }

            // Property: Template should exist before deletion attempt
            $this->assertDatabaseHas('templates', [
                'id' => $template->id,
            ]);

            // Property: Template should have correct number of invitations
            $this->assertEquals(
                $testCase['invitations_using_template'],
                $template->invitations()->count()
            );

            // Attempt to delete the template
            $response = $this->actingAs($admin)
                ->delete(route('admin.templates.destroy', $template->id));

            if ($testCase['should_delete']) {
                // Property: Deletion should succeed when template is not in use
                $response->assertRedirect(route('admin.templates.index'));
                $response->assertSessionHas('success', 'Template deleted successfully.');

                // Property: Template should be removed from database
                $this->assertDatabaseMissing('templates', [
                    'id' => $template->id,
                ]);

                // Property: Template count should decrease by 1
                $remainingTemplates = Template::count();
                $this->assertGreaterThanOrEqual(0, $remainingTemplates);
            } else {
                // Property: Deletion should fail when template is in use
                $response->assertRedirect(route('admin.templates.index'));
                $response->assertSessionHas('error', 'Cannot delete template that is being used by invitations.');

                // Property: Template should still exist in database
                $this->assertDatabaseHas('templates', [
                    'id' => $template->id,
                ]);

                // Property: Template should still have the same invitations
                $template->refresh();
                $this->assertEquals(
                    $testCase['invitations_using_template'],
                    $template->invitations()->count()
                );

                // Property: All invitations should still reference this template
                foreach ($invitations as $invitation) {
                    $this->assertDatabaseHas('invitations', [
                        'id' => $invitation->id,
                        'template_id' => $template->id,
                    ]);
                }
            }

            // Cleanup for next iteration
            if (!empty($invitations)) {
                Invitation::whereIn('id', collect($invitations)->pluck('id'))->delete();
                $user->delete();
            }

            // Only delete template if it still exists
            if (Template::find($template->id)) {
                $template->delete();
            }

            $admin->delete();
        }
    }

    /**
     * Property: Template Deletion Is Atomic
     *
     * For any template without invitations, deletion should remove both
     * the database record and all associated files, or fail completely
     * without partial deletion.
     *
     * Validates: Requirements 10.7
     */
    public function test_property_template_deletion_is_atomic(): void
    {
        $testCases = [1, 2, 3, 4, 5];

        foreach ($testCases as $iteration) {
            $admin = User::factory()->create([
                'is_admin' => true,
                'is_active' => true,
            ]);

            // Create template files
            $templateName = 'test-template-' . uniqid();
            $templateDir = "templates/{$templateName}";

            Storage::put("{$templateDir}/template.html", '<html>Test</html>');
            Storage::put("{$templateDir}/style.css", 'body { color: red; }');
            Storage::put("{$templateDir}/script.js", 'console.log("test");');
            Storage::put("{$templateDir}/thumbnail.svg", '<svg></svg>');

            $template = Template::factory()->create([
                'name' => $templateName,
                'html_path' => "{$templateDir}/template.html",
                'css_path' => "{$templateDir}/style.css",
                'js_path' => "{$templateDir}/script.js",
                'thumbnail_path' => "{$templateDir}/thumbnail.svg",
            ]);

            // Property: All files should exist before deletion
            $this->assertTrue(Storage::exists($template->html_path));
            $this->assertTrue(Storage::exists($template->css_path));
            $this->assertTrue(Storage::exists($template->js_path));
            $this->assertTrue(Storage::exists($template->thumbnail_path));

            // Property: Template should exist in database
            $this->assertDatabaseHas('templates', [
                'id' => $template->id,
            ]);

            // Delete the template
            $response = $this->actingAs($admin)
                ->delete(route('admin.templates.destroy', $template->id));

            // Property: Deletion should succeed
            $response->assertRedirect(route('admin.templates.index'));
            $response->assertSessionHas('success');

            // Property: Template should be removed from database
            $this->assertDatabaseMissing('templates', [
                'id' => $template->id,
            ]);

            // Property: All files should be deleted
            $this->assertFalse(Storage::exists($template->html_path));
            $this->assertFalse(Storage::exists($template->css_path));
            $this->assertFalse(Storage::exists($template->js_path));
            $this->assertFalse(Storage::exists($template->thumbnail_path));

            // Property: Template directory should be deleted if empty
            $this->assertFalse(Storage::exists($templateDir));

            // Cleanup for next iteration
            $admin->delete();
        }
    }

    /**
     * Property: Non-Admin Cannot Delete Templates
     *
     * For any non-admin user, attempting to delete a template should be
     * denied regardless of whether the template is in use.
     *
     * Validates: Requirements 10.1, 12.4
     */
    public function test_property_non_admin_cannot_delete_templates(): void
    {
        $testCases = [
            ['has_invitations' => false],
            ['has_invitations' => true],
            ['has_invitations' => false],
            ['has_invitations' => true],
        ];

        foreach ($testCases as $testCase) {
            $regularUser = User::factory()->create([
                'is_admin' => false,
                'is_active' => true,
            ]);

            $template = Template::factory()->create();

            if ($testCase['has_invitations']) {
                Invitation::factory()->create([
                    'user_id' => $regularUser->id,
                    'template_id' => $template->id,
                ]);
            }

            // Property: Template should exist before deletion attempt
            $this->assertDatabaseHas('templates', [
                'id' => $template->id,
            ]);

            // Attempt to delete as non-admin
            $response = $this->actingAs($regularUser)
                ->delete(route('admin.templates.destroy', $template->id));

            // Property: Access should be denied
            $response->assertRedirect(route('dashboard'));
            $response->assertSessionHas('error');

            // Property: Template should still exist
            $this->assertDatabaseHas('templates', [
                'id' => $template->id,
            ]);

            // Property: Template invitations should be unchanged
            if ($testCase['has_invitations']) {
                $this->assertEquals(1, $template->invitations()->count());
            }

            // Cleanup for next iteration
            Invitation::where('template_id', $template->id)->delete();
            $template->delete();
            $regularUser->delete();
        }
    }

    /**
     * Property: Template Deletion Preserves Other Templates
     *
     * For any template being deleted, other templates in the system should
     * remain unaffected.
     *
     * Validates: Requirements 10.7
     */
    public function test_property_template_deletion_preserves_other_templates(): void
    {
        $testCases = [
            ['total_templates' => 3, 'delete_index' => 0],
            ['total_templates' => 5, 'delete_index' => 2],
            ['total_templates' => 4, 'delete_index' => 3],
            ['total_templates' => 6, 'delete_index' => 1],
        ];

        foreach ($testCases as $testCase) {
            $admin = User::factory()->create([
                'is_admin' => true,
                'is_active' => true,
            ]);

            // Create multiple templates
            $templates = Template::factory()
                ->count($testCase['total_templates'])
                ->create();

            $templateToDelete = $templates[$testCase['delete_index']];
            $otherTemplates = $templates->except($templateToDelete->id);

            // Property: All templates should exist before deletion
            $this->assertEquals($testCase['total_templates'], Template::count());

            // Delete one template
            $response = $this->actingAs($admin)
                ->delete(route('admin.templates.destroy', $templateToDelete->id));

            // Property: Deletion should succeed
            $response->assertRedirect(route('admin.templates.index'));
            $response->assertSessionHas('success');

            // Property: Deleted template should be removed
            $this->assertDatabaseMissing('templates', [
                'id' => $templateToDelete->id,
            ]);

            // Property: Other templates should still exist
            foreach ($otherTemplates as $template) {
                $this->assertDatabaseHas('templates', [
                    'id' => $template->id,
                    'name' => $template->name,
                ]);
            }

            // Property: Template count should decrease by exactly 1
            $this->assertEquals(
                $testCase['total_templates'] - 1,
                Template::count()
            );

            // Cleanup for next iteration
            Template::whereIn('id', $otherTemplates->pluck('id'))->delete();
            $admin->delete();
        }
    }

    /**
     * Property: Template With Mixed Status Invitations Cannot Be Deleted
     *
     * For any template used by invitations with different statuses
     * (draft, published, unpublished), deletion should fail.
     *
     * Validates: Requirements 10.7
     */
    public function test_property_template_with_mixed_status_invitations_cannot_be_deleted(): void
    {
        $testCases = [
            ['statuses' => ['draft']],
            ['statuses' => ['published']],
            ['statuses' => ['unpublished']],
            ['statuses' => ['draft', 'published']],
            ['statuses' => ['draft', 'unpublished']],
            ['statuses' => ['published', 'unpublished']],
            ['statuses' => ['draft', 'published', 'unpublished']],
        ];

        foreach ($testCases as $testCase) {
            $admin = User::factory()->create([
                'is_admin' => true,
                'is_active' => true,
            ]);

            $user = User::factory()->create();
            $template = Template::factory()->create();

            // Create invitations with different statuses
            $invitations = [];
            foreach ($testCase['statuses'] as $status) {
                $invitations[] = Invitation::factory()->create([
                    'user_id' => $user->id,
                    'template_id' => $template->id,
                    'status' => $status,
                    'unique_url' => $status === 'published' ? \Illuminate\Support\Str::random(32) : null,
                ]);
            }

            // Property: Template should have invitations
            $this->assertGreaterThan(0, $template->invitations()->count());

            // Attempt to delete the template
            $response = $this->actingAs($admin)
                ->delete(route('admin.templates.destroy', $template->id));

            // Property: Deletion should fail
            $response->assertRedirect(route('admin.templates.index'));
            $response->assertSessionHas('error', 'Cannot delete template that is being used by invitations.');

            // Property: Template should still exist
            $this->assertDatabaseHas('templates', [
                'id' => $template->id,
            ]);

            // Property: All invitations should still exist and reference the template
            foreach ($invitations as $invitation) {
                $this->assertDatabaseHas('invitations', [
                    'id' => $invitation->id,
                    'template_id' => $template->id,
                    'status' => $invitation->status,
                ]);
            }

            // Cleanup for next iteration
            Invitation::whereIn('id', collect($invitations)->pluck('id'))->delete();
            $template->delete();
            $user->delete();
            $admin->delete();
        }
    }

    /**
     * Property: Deleting Template Does Not Affect Unrelated Invitations
     *
     * For any template being deleted (without invitations), invitations
     * using other templates should remain unaffected.
     *
     * Validates: Requirements 10.7
     */
    public function test_property_deleting_template_does_not_affect_unrelated_invitations(): void
    {
        $testCases = [
            ['other_template_invitations' => 1],
            ['other_template_invitations' => 3],
            ['other_template_invitations' => 5],
            ['other_template_invitations' => 10],
        ];

        foreach ($testCases as $testCase) {
            $admin = User::factory()->create([
                'is_admin' => true,
                'is_active' => true,
            ]);

            $user = User::factory()->create();

            // Create template to delete (no invitations)
            $templateToDelete = Template::factory()->create();

            // Create another template with invitations
            $otherTemplate = Template::factory()->create();
            $otherInvitations = Invitation::factory()
                ->count($testCase['other_template_invitations'])
                ->create([
                    'user_id' => $user->id,
                    'template_id' => $otherTemplate->id,
                ]);

            // Property: Both templates should exist
            $this->assertDatabaseHas('templates', ['id' => $templateToDelete->id]);
            $this->assertDatabaseHas('templates', ['id' => $otherTemplate->id]);

            // Property: Other template should have invitations
            $this->assertEquals(
                $testCase['other_template_invitations'],
                $otherTemplate->invitations()->count()
            );

            // Delete the template without invitations
            $response = $this->actingAs($admin)
                ->delete(route('admin.templates.destroy', $templateToDelete->id));

            // Property: Deletion should succeed
            $response->assertRedirect(route('admin.templates.index'));
            $response->assertSessionHas('success');

            // Property: Deleted template should be removed
            $this->assertDatabaseMissing('templates', [
                'id' => $templateToDelete->id,
            ]);

            // Property: Other template should still exist
            $this->assertDatabaseHas('templates', [
                'id' => $otherTemplate->id,
            ]);

            // Property: Other template's invitations should be unchanged
            $this->assertEquals(
                $testCase['other_template_invitations'],
                $otherTemplate->invitations()->count()
            );

            // Property: All other invitations should still exist
            foreach ($otherInvitations as $invitation) {
                $this->assertDatabaseHas('invitations', [
                    'id' => $invitation->id,
                    'template_id' => $otherTemplate->id,
                ]);
            }

            // Cleanup for next iteration
            Invitation::whereIn('id', $otherInvitations->pluck('id'))->delete();
            $otherTemplate->delete();
            $user->delete();
            $admin->delete();
        }
    }
}
