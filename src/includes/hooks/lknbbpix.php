<?php

use Lkn\BBPix\App\Pix\Controllers\DiscountController;
use Lkn\BBPix\App\Pix\Services\ConfirmPaymentService;
use Lkn\BBPix\App\Pix\Services\IsInvoicePixPaidService;
use Lkn\BBPix\Helpers\Config;
use Lkn\BBPix\Helpers\Invoice;
use Lkn\BBPix\Helpers\Logger;
use Lkn\BBPix\Helpers\View;
use WHMCS\Database\Capsule;

require_once __DIR__ . '/../../modules/gateways/lknbbpix/vendor/autoload.php';

add_hook('AdminInvoicesControlsOutput', 1, function (array $hookParams): string {
    if ($hookParams['paymentmethod'] !== 'lknbbpix') {
        return '';
    }

    $isInvoiceUnpaid = Capsule::table('tblinvoices')
        ->where('id', $hookParams['invoiceid'])
        ->where('status', 'Unpaid')
        ->exists();

    $transactions = localAPI('GetTransactions', ['invoiceid' => $hookParams['invoiceid']]);

    $transacs = $transactions['transactions']['transaction'] ?? [];

    $latestTransac = end($transacs);

    // The last invoice transaction must have been entered by the gateway.
    if ($latestTransac['gateway'] !== Config::constant('name')) {
        return '';
    }

    if (!$isInvoiceUnpaid) {
        return '';
    }

    return View::render(
        'admin_invoices_controls_output.index',
        [
            'enable_admin_manual_check' => Config::setting('enable_admin_manual_check') ?? false
        ]
    );
});

add_hook('AdminAreaHeaderOutput', 1, function (array $vars) {
    if (str_contains($_SERVER['PHP_SELF'], 'configgateways.php')) {
        return (new DiscountController())->index();
    }
});

add_hook('InvoiceCancelled', 1, function ($vars): void {
    $invoiceId = $vars['invoiceid'];

    if (!Config::setting('enable_pix_when_invoice_cancel')) {
        return;
    }

    $invoiceTrans = Invoice::getTransactions($invoiceId)['transactions']['transaction'];

    if (empty($invoiceTrans)) {
        return;
    }

    $lastInvoiceTrans = end($invoiceTrans);

    if ($lastInvoiceTrans['gateway'] !== 'lknbbpix' || !str_starts_with($lastInvoiceTrans['transid'], 'CRIADOx')) {
        Logger::log('Verificar se fatura está paga antes de cancelar', 'A última transação da fatura não é um Pix pendente.');

        return;
    }

    $isPixPaidResponse = (new IsInvoicePixPaidService())->run($invoiceId, $lastInvoiceTrans['transid']);

    if (is_bool($isPixPaidResponse)) {
        Logger::log('Verificar se fatura está paga antes de cancelar', 'O Pix não está pago.');

        return;
    }

    $apiTxId = $isPixPaidResponse['apiTxId'];
    $paidAmount = $isPixPaidResponse['paidAmount'];
    $paymentDate = $isPixPaidResponse['paymentDate'];
    $pixEndToEndId = $isPixPaidResponse['endToEndId'];

    (new ConfirmPaymentService())->run($apiTxId, $paidAmount, $paymentDate, $pixEndToEndId);

    $updateInvoiceResponse = localAPI('UpdateInvoice', ['invoiceid' => $invoiceId, 'status' => 'Paid']);

    Logger::log('Verificar se fatura está paga antes de cancelar', ['updateInvoiceResponse' => $updateInvoiceResponse]);
});
