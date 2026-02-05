@extends('layouts.app')

@section('title', 'My Wallet')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">My Wallet</h1>
                <p class="text-gray-600 mt-1">Manage your wallet balance</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">Current Balance</p>
                <p class="text-4xl font-bold text-indigo-600">BTN {{ number_format($user->wallet_balance, 2) }}</p>
            </div>
        </div>
        
        <form action="{{ route('wallet.add') }}" method="POST" class="mt-6">
            @csrf
            <div class="flex space-x-4">
                <div class="flex-1">
                    <input type="number" name="amount" min="1" step="0.01" required
                           placeholder="Enter amount to add"
                           class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700 font-semibold">
                    <i class="fas fa-plus"></i> Add Coins
                </button>
            </div>
            @error('amount')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </form>

        <div class="mt-4 grid grid-cols-3 md:grid-cols-6 gap-2">
            @foreach([100, 500, 1000, 2000, 5000, 10000] as $quickAmount)
                <button onclick="document.querySelector('[name=amount]').value={{ $quickAmount }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm">
                    BTN {{ $quickAmount }}
                </button>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Transaction History</h2>
        
        @if($transactions->count() > 0)
            <div class="space-y-3">
                @foreach($transactions as $transaction)
                    <div class="flex justify-between items-center border-b border-gray-200 pb-3">
                        <div class="flex-1">
                            <div class="flex items-center">
                                @if($transaction->type === 'credit')
                                    <i class="fas fa-arrow-down text-green-600 text-xl mr-3"></i>
                                    <div>
                                        <p class="font-semibold text-gray-900">Coins Added</p>
                                        <p class="text-sm text-gray-600">{{ $transaction->description }}</p>
                                    </div>
                                @elseif($transaction->type === 'debit')
                                    <i class="fas fa-arrow-up text-red-600 text-xl mr-3"></i>
                                    <div>
                                        <p class="font-semibold text-gray-900">Payment</p>
                                        <p class="text-sm text-gray-600">{{ $transaction->description }}</p>
                                    </div>
                                @elseif($transaction->type === 'refund')
                                    <i class="fas fa-undo text-blue-600 text-xl mr-3"></i>
                                    <div>
                                        <p class="font-semibold text-gray-900">Refund</p>
                                        <p class="text-sm text-gray-600">{{ $transaction->description }}</p>
                                    </div>
                                @endif
                            </div>
                            <p class="text-xs text-gray-400 mt-1">{{ $transaction->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold {{ $transaction->type === 'credit' || $transaction->type === 'refund' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transaction->type === 'debit' ? '-' : '+' }}BTN {{ number_format($transaction->amount, 2) }}
                            </p>
                            <p class="text-xs text-gray-500">Balance: BTN {{ number_format($transaction->balance_after, 2) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-6">
                {{ $transactions->links() }}
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-receipt text-gray-400 text-6xl mb-4"></i>
                <p class="text-gray-600">No transactions yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection
