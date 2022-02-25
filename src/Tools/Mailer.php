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

        foreach ($reports as $report) {
            if ($report['html'] ?? false) {
                $email = $email->html($report['report']);
            } else {
                $email = $email->text($report['report']);
            }
        }

        $mailer->send($email);
    }
}
