<?php

/**
 * This file handle requests that come primary from the module configuration pages.
 *
 * These requests are made by JS fetch(). They are primary located in /resources/js
 * and others are located directly in a .tpl file.
 */

use Lkn\BBPix\App\Pix\Controllers\ApiController;
use Lkn\BBPix\App\Pix\Controllers\DiscountController;
use Lkn\BBPix\App\Pix\PixController;
use Lkn\BBPix\Helpers\Auth;
use Lkn\BBPix\Helpers\Config;
use Lkn\BBPix\Helpers\Response;
use WHMCS\Authentication\CurrentUser;
use WHMCS\Database\Capsule;

require_once __DIR__ . '/../../../init.php';
require_once __DIR__ . '/vendor/autoload.php';

$request = json_decode(file_get_contents('php://input')) ?? (object) ($_POST);

header('Content-Type: application/json;');

$authState = new CurrentUser();

switch ($request->action) {
    // The form request this endpoint to check if the invoice is paid each 18 seconds.
    case 'check-invoice-status':
        if (!isset($request->token) || $request->token !== $_SESSION['lkn-bb-pix']) {
            exit('token invalido.');
        }

        $invoiceId = $request->invoiceId;

        $isInvoicePaid = Capsule::table('tblinvoices')
            ->where('id', $invoiceId)
            ->where('status', 'Paid')
            ->exists();

        http_response_code(200);
        Response::api(true, ['isInvoicePaid' => $isInvoicePaid]);

        break;

    case 'manual-payment-confirmation':

        if (!($authState->admin() || $authState->client())) {
            exit;
        }

        $cobType = Config::setting('enable_fees_interest') ? 'cobv' : 'cob';

        http_response_code(200);
        return (new PixController($cobType))->checkAndConfirmInvoicePayment($request->invoiceId);

        break;

    case 'save-discount':
        if (!Auth::isAdminLogged(['Configure Payment Gateways'])) {
            exit;
        }

        http_response_code(200);
        (new DiscountController())->createOrUpdate($request->productId, $request->percentage);

        break;

    case 'delete-discount':
        if (!Auth::isAdminLogged(['Configure Payment Gateways'])) {
            exit;
        }

        http_response_code(200);
        (new DiscountController())->delete($request->productId);

        break;

    case 'update-private-cert':
    case 'update-public-cert':
        if (!$authState->admin()) {
            exit;
        }

        $certType = $request->action === 'update-private-cert' ? 'private' : 'public';
        http_response_code(200);
        (new ApiController())->updateCert($certType, $_FILES['cert']);

        break;

    default:
        http_response_code(404);

        break;
}
