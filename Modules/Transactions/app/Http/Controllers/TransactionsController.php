<?php

namespace Modules\Transactions\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Transactions\app\Helpers\ResponseData;
use Modules\Transactions\App\Http\Requests\TransactionRequest;
use Modules\Transactions\app\Services\PaymentService;

class TransactionsController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * معالجة المعاملات بأنواعها المختلفة
     */

    public function processTransaction(TransactionRequest $request)
    {
        // The data has already been validated by the TransactionRequest
        try {
            // Switch based on transaction type
            switch ($request->transaction_type) {
                case 'sale':
                    $this->paymentService->handleSale($request);
                    break;
                case 'installment':
                    $this->paymentService->handleInstallments($request);
                    break;
                case 'rent':
                    $this->paymentService->handleRent($request);
                    break;
                default:
                    return ResponseData::send('error', __('messages.transaction_type_error'));
            }

            // Return success response
            return ResponseData::send('success', __('messages.transaction_success'));

        } catch (\Exception $e) {
            // Return error response with the exception message
            return ResponseData::send('error', __('messages.error_message', ['message' => $e->getMessage()]));
        }
    }
}
