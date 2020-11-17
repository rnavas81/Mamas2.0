<?php
/**
 * Clase para enviar correos desde una cuenta definida en constantes
 *
 * @author rodrigo
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require_once '../libs/phpmailer/src/Exception.php';
require_once '../libs/phpmailer/src/PHPMailer.php';
require_once '../libs/phpmailer/src/SMTP.php';
class Correo {
    // Valores para realizar la conexión con la cuenta de correo saliente
    private const _HOST_ = "smtp.gmail.com";
    private const _USERNAME_="AuxiliarDAW2@gmail.com";
    private const _PASSWORD_="Chubaca20";
    private static $mail;
    /**
     * Envio de correo desde una cuenta constante
     * @param type $destino cuenta de correo que recibira el mensaje
     * @param type $asunto texto emitido en el asunto
     * @param type $cuerpo texto /html introducido en el cuerpo del correo
     * @return boolean
     */
    public static function enviar($destino="",$asunto="",$cuerpo="") {
        self::$mail = new PHPMailer();
        $enviado=false;
        try {
//          $mail->SMTPDebug = 2;                       // Sacar esta línea para no mostrar salida debug
            self::$mail->isSMTP();
            self::$mail->Host = self::_HOST_;           // Host de conexión SMTP
            self::$mail->SMTPAuth = true;
            self::$mail->Username = self::_USERNAME_;   // Usuario SMTP
            self::$mail->Password = self::_PASSWORD_;   // Password SMTP
            self::$mail->SMTPSecure = 'ssl';            // Activar seguridad TLS
            self::$mail->Port = 465;                    // Puerto SMTP
            #self::$mail->SMTPOptions = [               // Descomentar si el servidor SMTP tiene un certificado autofirmado
            #    'ssl'=> ['allow_self_signed' => true]
            #];  
            #self::$mail->SMTPSecure = false;		// Descomentar si se requiere desactivar cifrado (se suele usar en conjunto con la siguiente línea)
            #self::$mail->SMTPAutoTLS = false;		// Descomentar si se requiere desactivar completamente TLS (sin cifrado)

            self::$mail->setFrom(self::_USERNAME_);     // Mail del remitente
            self::$mail->addAddress($destino);          // Mail del destinatario

            self::$mail->isHTML(true);                  // El cuerpo contiene elementos html
            self::$mail->Subject = utf8_decode($asunto);             // Asunto del mensaje
            self::$mail->Body = utf8_decode($cuerpo);                // Contenido del mensaje (acepta HTML)
            //self::$mail->AltBody = $cuerpoAlt;        // Contenido del mensaje alternativo (texto plano)

            self::$mail->send();
            $enviado = true;
        } catch (Exception $e) {
            $enviado = false;
        } finally {
            return $enviado;
        }
        
    }
}
