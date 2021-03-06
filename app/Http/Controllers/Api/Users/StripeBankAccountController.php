<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\ApiController;
use App\Repositories\Contracts\UserRepositoryContract;
use Freevital\Stripe\StripeManager;

class StripeBankAccountController extends ApiController
{
    /**
     * @var UserRepositoryContract
     */
    protected $userRepository;

    /**
     * @var mixed
     */
    protected $user;

    /**
     * @var StripeManager
     */
    protected $stripeManager;

    /**
     * @param UserRepositoryContract $userRepository
     * @param StripeManager          $stripeManager
     */
    public function __construct(
        UserRepositoryContract $userRepository,
        StripeManager $stripeManager
    ) {
        $this->userRepository = $userRepository;
        $this->stripeManager = $stripeManager;

        $this->middleware(function ($request, $next) {
            $this->user = $this->userRepository->find(auth()->id());

            return $next($request);
        });
    }

    /**
     * Get stripe customer bank accounts.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $customer = $this->stripeManager->customer($this->user)->retrieve();

        if (!$customer) {
            return response()->jsonNotFound();
        }

        $bank_accounts = $customer->bankAccounts();

        return response()->json(compact('bank_accounts'));
    }
}
