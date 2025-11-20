<?php
namespace Infrastructure\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService {

    private $mailer;

    public function __construct() {
        $this->mailer = new PHPMailer(true);
        $this->configure();
    }

    private function configure() {
        try {
            // Configuración del servidor
            // $this->mailer->SMTPDebug = 2; // Descomentar para depuración
            $this->mailer->isSMTP();
            $this->mailer->Host       = MAIL_HOST;
            $this->mailer->SMTPAuth   = true;
            $this->mailer->Username   = MAIL_USERNAME;
            $this->mailer->Password   = MAIL_PASSWORD;
            $this->mailer->SMTPSecure = MAIL_ENCRYPTION;
            $this->mailer->Port       = MAIL_PORT;
            $this->mailer->CharSet    = 'UTF-8';

            // Remitente
            $this->mailer->setFrom(MAIL_FROM_ADDRESS, MAIL_FROM_NAME);

        } catch (Exception $e) {
            // Podrías loggear este error en un archivo en lugar de mostrarlo
            error_log("Error al configurar el mailer: {$this->mailer->ErrorInfo}");
        }
    }

    public function send(string $toAddress, string $toName, string $subject, string $body, string $altBody = ''): bool {
        try {
            // Destinatarios
            $this->mailer->addAddress($toAddress, $toName);

            // Contenido
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body    = $body;
            $this->mailer->AltBody = $altBody ?: strip_tags($body);

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            error_log("Error al enviar correo a {$toAddress}: {$this->mailer->ErrorInfo}");
            return false;
        }
    }
}