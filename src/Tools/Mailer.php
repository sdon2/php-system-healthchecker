<?php

namespace HealthChecker\Tools;

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer as SymfonyMailer;
use Symfony\Component\Mime\Email;

class Mailer
{
    public static function sendReport($reports)
    {
        $transport = Transport::fromDsn(sprintf("%s://%s:%s@%s:%s", $_ENV['EMAIL_TYPE'], urlencode($_ENV['EMAIL_USERNAME']), urlencode($_ENV['EMAIL_PASSWORD']), $_ENV['EMAIL_HOST'], $_ENV['EMAIL_PORT']));
        $mailer = new SymfonyMailer($transport);

        $email = (new Email())
            ->from($_ENV['EMAIL_FROM'])
            ->to($_ENV['EMAIL_FROM'])
            ->subject($_ENV['EMAIL_SUBJECT'] . " from " . $_ENV['SERVER_NAME']);

        $content = "";
        foreach ($reports as $report) {
            if ($report['html'] ?? false) {
                $content .= $report['report'];
            } else {
                $content .= nl2br(htmlspecialchars($report['report']));
            }
        }

        $email->html($content);

        $mailer->send($email);
    }
}
