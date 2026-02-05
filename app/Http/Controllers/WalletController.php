<?php

namespace App\Http\Controllers;

use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function __construct(
        protected WalletService $walletService
    ) {}

    public function index()
    {
        $user = Auth::user();
        $transactions = $this->walletService->getHistory($user);

        return view('wallet.index', compact('user', 'transactions'));
    }

    public function addCoins(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1|max:10000',
        ]);

        try {
            $this->walletService->addCoins(
                Auth::user(),
                $validated['amount'],
                'Manual credit'
            );

            return redirect()
                ->route('wallet.index')
                ->with('success', 'Coins added successfully!');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
