<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Donation\DonationPayment;
use App\Exceptions\Card\InvalidCardException;
use App\Exceptions\Donation\DonationNotFoundException;
use App\Exceptions\DonorPayment\InvalidProductException;
use App\Exceptions\Email\EmailNotSendException;
use App\Helpers\Response\ResponseAPI;
use App\Http\Requests\PaymentRequest;
use App\Mail\TransactionFailedMail;
use App\Mail\TransactionSuccessMail;
use App\Models\Donation;
use App\Models\Donor;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    /**
     * @param PaymentRequest $request
     * @param Donor $donor
     * @return JsonResponse
     * @throws EmailNotSendException
     * @throws InvalidCardException
     * @throws InvalidProductException|DonationNotFoundException
     */
    public function create(PaymentRequest $request, Donor $donor): JsonResponse
    {
        $provider = new DonationPayment();

        if ($provider->donationByInternalCheckout($request->all(), $donor)) {
            try {
                Mail::to($donor->email)->send(new TransactionSuccessMail($donor->name, $donor->email));
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
//                throw new EmailNotSendException($e);
            }

            return ResponseAPI::results(['message' => trans('payment.transaction.succeeded')]);
        } else {
            try {
                $data = Donation::getDonationRefusedByDonorId($donor);

                if (empty($data)) {
                    throw new DonationNotFoundException();
                }

                Mail::to($donor->email)->send(new TransactionFailedMail($donor->name, $donor->email, $data));
            } catch (DonationNotFoundException $e) {
                throw new DonationNotFoundException();
            } catch (\Exception $e) {
                throw new EmailNotSendException($e);
            }

            return ResponseAPI::unprocessableEntity(['message' => trans('payment.transaction.refused')]);
        }

    }
}
