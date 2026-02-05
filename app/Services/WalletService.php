<?php

namespace App\Services;

use App\Models\User;
use App\Models\WalletTransaction;
use App\Notifications\WalletCredited;
use Illuminate\Support\Facades\DB;

class WalletService
{
    /**
     * Add coins to user wallet
     */
    public function addCoins(User $user, float $amount, string $description = 'Manual credit', bool $sendNotification = true): WalletTransaction
    {
        return DB::transaction(function () use ($user, $amount, $description, $sendNotification) {
            $balanceBefore = $user->wallet_balance;
            $balanceAfter = $balanceBefore + $amount;

            $user->update(['wallet_balance' => $balanceAfter]);

            $transaction = WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'credit',
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'description' => $description,
            ]);

            // Send notification
            if ($sendNotification) {
                $user->notify(new WalletCredited($amount, $description, $balanceAfter));
            }

            return $transaction;
        });
    }

    /**
     * Deduct coins from user wallet
     */
    public function deductCoins(User $user, float $amount, string $description, ?int $bookingId = null): WalletTransaction
    {
        return DB::transaction(function () use ($user, $amount, $description, $bookingId) {
            if ($user->wallet_balance < $amount) {
                throw new \Exception('Insufficient wallet balance');
            }

            $balanceBefore = $user->wallet_balance;
            $balanceAfter = $balanceBefore - $amount;

            $user->update(['wallet_balance' => $balanceAfter]);

            return WalletTransaction::create([
                'user_id' => $user->id,
                'booking_id' => $bookingId,
                'type' => 'debit',
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'description' => $description,
            ]);
        });
    }

    /**
     * Refund coins to user wallet
     */
    public function refundCoins(User $user, float $amount, string $description, ?int $bookingId = null): WalletTransaction
    {
        return DB::transaction(function () use ($user, $amount, $description, $bookingId) {
            $balanceBefore = $user->wallet_balance;
            $balanceAfter = $balanceBefore + $amount;

            $user->update(['wallet_balance' => $balanceAfter]);

            $transaction = WalletTransaction::create([
                'user_id' => $user->id,
                'booking_id' => $bookingId,
                'type' => 'refund',
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'description' => $description,
            ]);

            // Send notification for refund
            $user->notify(new WalletCredited($amount, $description, $balanceAfter));

            return $transaction;
        });
    }

    /**
     * Get user wallet history
     */
    public function getHistory(User $user, int $perPage = 20)
    {
        return $user->walletTransactions()
            ->with('booking')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get wallet balance
     */
    public function getBalance(User $user): float
    {
        return (float) $user->wallet_balance;
    }
}
