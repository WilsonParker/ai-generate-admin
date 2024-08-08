<?php

namespace Tests\Feature\Mail;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Service\Mail\MailService;
use App\Service\Mail\Model\Template;
use Tests\TestCase;

class MailTest extends TestCase
{
    /*public function test_update_all_mail_templates(): void
    {
        $service = app()->make(MailService::class);
        $templates = $service->getTemplates();
        $templates->each(fn($template) => $template->delete());
        $templates->each(fn($template) => $template->save());
    }*/

    /**
     * A basic test example.
     */
    public function test_mail_get_templates(): void
    {
        $service = app()->make(MailService::class);
        $templates = $service->getTemplates();
        $this->assertTrue($templates->first()->getSender() !== null);
    }

    public function test_mail_get_template_params(): void
    {
        $service = app()->make(MailService::class);
        $template = $service->getTemplate(18);
        $params = $service->getParams($template->getHtmlContent());
        $this->assertIsArray($params->toArray());
    }

    public function test_save_mail_templates(): void
    {
        $service = app()->make(MailService::class);
        $templates = $service->getTemplates();
        Template::query()->delete();
        $templates->each(fn($template) => $template->save());
    }

    public function test_get_sended_mail_templates(): void
    {
        $messageId = "<202305260830.38514403669@smtp-relay.mailin.fr>";
        $service = app()->make(MailService::class);
        $log = $service->getLog($messageId);
        dd($log);
    }
}
