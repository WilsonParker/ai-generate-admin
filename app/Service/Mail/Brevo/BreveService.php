<?php

namespace App\Service\Mail\Brevo;

use App\Service\Mail\Contracts\MailContract;
use App\Service\Mail\Contracts\UserMailable;
use App\Service\Mail\MaiParameterComposite;
use App\Service\Mail\Model\Contracts\Receiver;
use App\Service\Mail\Model\Contracts\Sender;
use App\Service\Mail\Model\Contracts\Template;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use SendinBlue\Client\Api\ConversationsApi;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Model\GetSmtpTemplateOverview;
use SendinBlue\Client\Model\SendSmtpEmail;

class BreveService implements MailContract
{
    private $transactionalEmailsApi;
    private $conversationApi;

    public function __construct(
        private readonly string                $apiKey,
        private readonly MaiParameterComposite $composite
    ) {
        // Configure API key authorization: api-key
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $apiKey);

        $this->transactionalEmailsApi = new TransactionalEmailsApi(
        // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
        // This is optional, `GuzzleHttp\Client` will be used as default.
            new Client(),
            $config
        );

        $this->conversationApi = new ConversationsApi(
        // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
        // This is optional, `GuzzleHttp\Client` will be used as default.
            new Client(),
            $config
        );
    }

    public function send(Template $template, Sender $from, Receiver $to, UserMailable $mailable)
    {
        $params = $this->composite->bindParams($this->getParams($template->getHtmlContent()), [
            'user' => $mailable,
        ]);
        $sendSmtpEmail = new SendSmtpEmail(
            [
                'sender' => [
                    'name' => $from->getName(),
                    'email' => $from->getEmail(),
                ],
                'to' => [
                    [
                        'email' => $to->getEmail(),
                        'name' => $to->getName(),
                    ],
                ],
                'templateId' => $template->getId(),
                'params' => $params['params'],
            ]
        );
        $sendSmtpEmail = $this->transactionalEmailsApi->sendTransacEmail($sendSmtpEmail);
    }

    public function getTemplates(): Collection
    {
        return collect($this->transactionalEmailsApi->getSmtpTemplates()->getTemplates())
            ->map(fn(GetSmtpTemplateOverview $template) => new \App\Service\Mail\Model\Template([
                'id' => $template->getId(),
                'name' => $template->getName(),
                'subject' => $template->getSubject(),
                'is_active' => $template->getIsActive(),
                'test_sent' => $template->getTestSent(),
                'sender' => $template->getSender(),
                'reply_to' => $template->getReplyTo(),
                'to_field' => $template->getToField(),
                'tag' => $template->getTag(),
                'html_content' => $template->getHtmlContent(),
                'created_at' => $template->getCreatedAt(),
                'updated_at' => $template->getModifiedAt(),
            ]));
    }

    public function getLog(string $id)
    {
        return $this->conversationApi->conversationsMessagesIdGet($id);
    }

    public function getTemplate(string $id): Template
    {
        $template = $this->transactionalEmailsApi->getSmtpTemplate($id);
        return new \App\Service\Mail\Model\Template([
            'id' => $template->getId(),
            'name' => $template->getName(),
            'subject' => $template->getSubject(),
            'is_active' => $template->getIsActive(),
            'test_sent' => $template->getTestSent(),
            'sender' => $template->getSender(),
            'reply_to' => $template->getReplyTo(),
            'to_field' => $template->getToField(),
            'tag' => $template->getTag(),
            'html_content' => $template->getHtmlContent(),
            'created_at' => $template->getCreatedAt(),
            'updated_at' => $template->getModifiedAt(),
        ]);
    }

    public function getParams(string $content): array
    {
        $pattern = '/\{\{(params.*?)\}\}/';
        preg_match_all($pattern, $content, $matches);
        return collect($matches[1])->unique()->filter()->toArray();
    }
}