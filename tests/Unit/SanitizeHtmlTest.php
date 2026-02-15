<?php

namespace Tests\Unit;

use App\Rules\SanitizeHtml;
use Tests\TestCase;

class SanitizeHtmlTest extends TestCase
{
    private SanitizeHtml $rule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rule = new SanitizeHtml();
    }

    public function test_passes_with_plain_text(): void
    {
        $passes = true;
        $this->rule->validate('test', 'Hello World', function () use (&$passes) {
            $passes = false;
        });

        $this->assertTrue($passes);
    }

    public function test_passes_with_safe_html(): void
    {
        $passes = true;
        $this->rule->validate('test', '<p>Hello <strong>World</strong></p>', function () use (&$passes) {
            $passes = false;
        });

        $this->assertTrue($passes);
    }

    public function test_fails_with_script_tag(): void
    {
        $passes = true;
        $this->rule->validate('test', '<script>alert("xss")</script>', function () use (&$passes) {
            $passes = false;
        });

        $this->assertFalse($passes);
    }

    public function test_fails_with_javascript_protocol(): void
    {
        $passes = true;
        $this->rule->validate('test', '<a href="javascript:alert(1)">Click</a>', function () use (&$passes) {
            $passes = false;
        });

        $this->assertFalse($passes);
    }

    public function test_fails_with_onclick_attribute(): void
    {
        $passes = true;
        $this->rule->validate('test', '<div onclick="alert(1)">Click</div>', function () use (&$passes) {
            $passes = false;
        });

        $this->assertFalse($passes);
    }

    public function test_fails_with_onerror_attribute(): void
    {
        $passes = true;
        $this->rule->validate('test', '<img src="x" onerror="alert(1)">', function () use (&$passes) {
            $passes = false;
        });

        $this->assertFalse($passes);
    }

    public function test_passes_with_null_value(): void
    {
        $passes = true;
        $this->rule->validate('test', null, function () use (&$passes) {
            $passes = false;
        });

        $this->assertTrue($passes);
    }

    public function test_passes_with_empty_string(): void
    {
        $passes = true;
        $this->rule->validate('test', '', function () use (&$passes) {
            $passes = false;
        });

        $this->assertTrue($passes);
    }

    public function test_passes_with_allowed_tags(): void
    {
        $passes = true;
        $safeHtml = '<p>Text with <strong>bold</strong> and <em>italic</em> and <a href="https://example.com">link</a></p>';
        $this->rule->validate('test', $safeHtml, function () use (&$passes) {
            $passes = false;
        });

        $this->assertTrue($passes);
    }

    public function test_fails_with_iframe_tag(): void
    {
        $passes = true;
        $this->rule->validate('test', '<iframe src="evil.com"></iframe>', function () use (&$passes) {
            $passes = false;
        });

        $this->assertFalse($passes);
    }

    public function test_fails_with_object_tag(): void
    {
        $passes = true;
        $this->rule->validate('test', '<object data="evil.swf"></object>', function () use (&$passes) {
            $passes = false;
        });

        $this->assertFalse($passes);
    }

    public function test_fails_with_embed_tag(): void
    {
        $passes = true;
        $this->rule->validate('test', '<embed src="evil.swf">', function () use (&$passes) {
            $passes = false;
        });

        $this->assertFalse($passes);
    }

    public function test_passes_with_encoded_entities(): void
    {
        $passes = true;
        $this->rule->validate('test', '&lt;script&gt;alert(1)&lt;/script&gt;', function () use (&$passes) {
            $passes = false;
        });

        $this->assertTrue($passes);
    }

    public function test_fails_with_data_protocol(): void
    {
        $passes = true;
        $this->rule->validate('test', '<a href="data:text/html,<script>alert(1)</script>">Click</a>', function () use (&$passes) {
            $passes = false;
        });

        $this->assertFalse($passes);
    }

    public function test_passes_with_multiline_safe_html(): void
    {
        $passes = true;
        $safeHtml = "<p>Line 1</p>\n<p>Line 2</p>\n<p>Line 3</p>";
        $this->rule->validate('test', $safeHtml, function () use (&$passes) {
            $passes = false;
        });

        $this->assertTrue($passes);
    }

    public function test_fails_with_mixed_safe_and_unsafe_content(): void
    {
        $passes = true;
        $this->rule->validate('test', '<p>Safe text</p><script>alert(1)</script>', function () use (&$passes) {
            $passes = false;
        });

        $this->assertFalse($passes);
    }
}
