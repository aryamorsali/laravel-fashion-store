<?php

namespace App\Http\Services\Payment;

use App\Models\Market\Payment;

use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment as PaymentShetabit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function zarinpal(int $amount, $order, $payment)
    {
        $callbackUrl = route('customer.sales-process.payment-call-back', [
            'order' => $order->id,
            'payment' => $payment->id
        ]);

        // است بخاطر همین  T 100 = R 1000 حداقل مبلغ قابل قبول در زرین پال 
        $amount = $amount * 10;

        $invoice = (new Invoice)->amount((int)$amount)
            ->detail('پرداخت سفارش شماره ' . $order->id);

        try {
            //  ارسال درخواست به درگاه
            return PaymentShetabit::callbackUrl($callbackUrl)->purchase($invoice, function ($driver, $transactionId) use ($payment, $amount) {
                $payment->update([
                    'transaction_id' => $transactionId,
                    'first_response' => [
                        'transaction_id' => $transactionId,   // Authority
                        'gateway' => 'zarinpal',
                        'driver_class' => get_class($driver),
                        'amount' => $amount,
                        'currency' => 'T',
                        'created_at' => now()->toDateTimeString(),
                    ],
                ]);
            })->pay()->render();
        } catch (\Exception $e) {
            return redirect()->back()->with('toast-error', 'خطا در اتصال به درگاه پرداخت: ' . $e->getMessage());
        }
    }


    public function zarinpalVerify(Request $request, Payment $payment)
    {
        $authority = (string) $request->input('Authority');

        if (empty($authority)) {
            return [
                'status'  => 'invalid_authority',
                'message' => 'کد مرجع تراکنش (Authority) نامعتبر یا خالی است.',
            ];
        }


        if ($payment->transaction_id && !hash_equals((string) $payment->transaction_id, $authority)) {
            return [
                'status'  => 'mismatched_authority',
                'message' => 'شناسه Authority با تراکنش ثبت‌ شده مطابقت ندارد.',
            ];
        }

        if ($request->input('Status') !== 'OK') {
            return [
                'status'  => 'canceled_by_user',
                'message' => 'پرداخت توسط کاربر لغو شد.',
                'payload' => [
                    'status'     => $request->input('Status'),
                    'authority'  => $authority,
                    'time'       => now()->toDateTimeString(),
                    'ip'         => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]
            ];
        }

        try {
            // استعلام نهایی از زرین‌ پال
            // میشود catach اگر تایید ناموفق باشد وارد  
            $receipt = PaymentShetabit::amount((int) $payment->amount * 10)
                ->transactionId($authority)
                ->verify();

            return [
                'status'       => 'success',
                'message'      => 'پرداخت با موفقیت تایید شد.',
                'reference_id' => $receipt->getReferenceId(),
                'driver'       => $receipt->getDriver(),
                'details'      => $receipt->getDetails(),
            ];
        } catch (\Exception $e) {
            return [
                'status'  => 'verification_failed',
                'message' => 'تایید پرداخت ناموفق بود: ' . $e->getMessage(),
            ];
        }
    }

    function resultCodes($code)
    {
        switch ($code) {
            case 100:
                return 'با موفقیت تایید شد';
                // break;
            case 102:
                return 'merchant یافت نشد';
            case 103:
                return 'merchant غیرفعال';
            case 104:
                return 'merchant نامعتبر';
            case 201:
                return "قبلا تایید شده";
            case 105:
                return "amount بایستی بزرگتر از 1,000 ریال باشد";
            case 106:
                return "callbackUrl نامعتبر میباشد. (شروع با https و یا http)";
            case 113:
                return "amount مبلغ تراکنش از سقف میزان تراکنش بیشتر است";
            case 202:
                return "سفارش پرداخت نشده یا ناموفق بوده است";
            case 203:
                return "trackId نامعتبر میباشد";
            default:
                return "وضعیت مشخص شده معتبر نیست";
        }
    }

    function statusCodes($code)
    {
        switch ($code) {
            case -1:
                return "در انتظار پرداخت";
            case -2:
                return "خطای داخلی";
            case 1:
                return "پرداخت شده - تایید شده";
            case 2:
                return "پرداخت شده - تایید نشده";
            case 3:
                return "لغو شده توسط کاربر";
            case 4:
                return "شماره کارت نامعتبر می باشد";
            case 5:
                return "موجودی حساب کافی نمی باشد";
            case 6:
                return "رمز وارد شده اشتباه می باشد";
            case 7:
                return "تعداد درخواست ها بیش از حد مجاز می باشد";
            case 8:
                return "تعداد پرداخت اینترنتی روزانه بیش از حد مجاز می باشد";
            case 9:
                return "مبلغ پرداخت اینترنتی روزانه بیش از حد مجاز می باشد";
            case 10:
                return "صادر کننده کارت نامعتبر میباشد";
            case 11:
                return "خطای سوییچ";
            case 12:
                return "کارت قابل دسترسی نمیباشد";
            default:
                return "وضعیت مشخص شده معتبر نیست";
        }
    }
}
