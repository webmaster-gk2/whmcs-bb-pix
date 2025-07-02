<?php

namespace Lkn\BBPix\App\Pix\Controllers;

use Lkn\BBPix\Helpers\Logger;
use Throwable;

final class ApiController
{
    /**
     * @since 2.0.0
     *
     * @param string $certType         "private" or "public".
     * @param array  $uploadedCertFile
     *
     * @return void
     */
    public function updateCert(string $certType, array $uploadedCertFile): void
    {
        try {
            $savePath = __DIR__ . "/../../../../certs/$certType.key";
            $certTypeLabel = $certType === 'private' ? 'privado' : 'público';

            if (move_uploaded_file($uploadedCertFile['tmp_name'], $savePath)) {
                $response = ['success' => true, 'msg' => "Certificado {$certTypeLabel} atualizado."];
                $logMessage = 'Certificado atualizado.';
            } else {
                $response = ['success' => false, 'msg' => 'Erro ao enviar certificado para o servidor. Certificado não atualizado.'];
                $logMessage = 'Erro ao enviar certificado para o servidor. Certificado não atualizado.';
            }
        } catch (Throwable $e) {
            $response = ['success' => false, 'msg' => $e->getMessage()];
            $logMessage = 'Erro ao tentar atualizar certificado. Erro: ' . $e;
        }

        Logger::log(
            'Atualizar certificado',
            [
                'certType' => $certType,
                'uploadedCertFile' => $uploadedCertFile
            ],
            $logMessage
        );

        header('Content-Type: application/json');
        echo json_encode($response);
    }
}
