<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Bavix\Wallet\Models\Wallet;

class WalletController extends Controller
{
    // Wallet creation
    public function createWallet(Request $request)
    {
        $user = User::find(1);
        $wallet = new Wallet();
        $wallet = $user->wallet()->save($wallet);

        return response()->json(['wallet' => $wallet]);
    }

    // Recharge wallet
    public function rechargeWallet(Request $request)
    {
        $amount = $request->input('amount');
        $wallet = $request->user()->wallet;
        $wallet->credit($amount); // Credit wallet balance

        return response()->json(['balance' => $wallet->balance]);
    }

    // Transfer funds
    public function transferFunds(Request $request)
    {
        $amount = $request->input('amount');
        $receiver = User::find($request->input('receiver_id'));
        $senderWallet = $request->user()->wallet;
        $receiverWallet = $receiver->wallet;

        // Deduct commission from sender
        $commissionPercentage = 2; // Example 2% commission
        $commission = ($amount * $commissionPercentage) / 100;
        $admin = User::find(1); // Admin user
        $adminWallet = $admin->wallet;

        // Transaction logic
        $senderWallet->debit($amount + $commission);
        $receiverWallet->credit($amount);
        $adminWallet->credit($commission);

        return response()->json(['message' => 'Transfer successful']);
    }

    // Transaction history
    public function transactionHistory(Request $request)
    {
        $transactions = $request->user()->wallet->transactions;
        return response()->json(['transactions' => $transactions]);
    }
}

