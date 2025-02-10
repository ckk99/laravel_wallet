<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
// use Bavix\Wallet\Wallets\Models\Transaction;
use Bavix\Wallet\Models\Transaction;
use Bavix\Wallet\Models\Transfer;
use Bavix\Wallet\Models\Wallet;
use Bavix\Wallet\Models\Wallet as WalletModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    // Wallet Creation
    public function createWallet(Request $request)
    {
        $user = Auth::user();

        if (!$user->wallet) {
            $user->wallet()->create();  // Creating the wallet for the user
        }

        return response()->json(['message' => 'Wallet created successfully', 'wallet' => $user->wallet]);
    }

    // Wallet Balance
    public function balance()
    {
        $wallet = Auth::user()->wallet;
        return response()->json(['balance' => $wallet->balance]);  // Display wallet balance
    }

    // Credit Balance
    public function credit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $amount = $request->input('amount');
        $wallet = Auth::user()->wallet;

        $wallet->deposit($amount);  // credit() balance deposit()
        return response()->json(['message' => 'Amount credited successfully', 'balance' => $wallet->balance]);
    }

    // Debit Balance
    public function debit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $amount = $request->input('amount');
        $wallet = Auth::user()->wallet;

        if ($wallet->balance >= $amount) {
            $wallet->withdraw($amount);  // debit() balance withdraw()
            return response()->json(['message' => 'Amount debited successfully', 'balance' => $wallet->balance]);
        } else {
            return response()->json(['message' => 'Insufficient balance'], 400);
        }
    }

    // Transaction History
    public function transactionHistory()
    {
        $transactions = Auth::user()->wallet->transactions;  // Get all wallet transactions
        return response()->json($transactions);
    }

    // Wallet to Wallet Transfer
    public function transfer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required',
            'receiver_user_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $amount = $request->input('amount');
        $receiverUserId = $request->input('receiver_user_id');
        $wallet = Auth::user()->wallet;

        $receiver = User::findOrFail($receiverUserId);
        $receiverWallet = $receiver->wallet;

        /// Get the sender's wallet
        $wallet = Auth::user()->wallet;  
        // Check if the sender has enough balance
        if ($wallet->balance >= $amount) {
            // Perform the transfer: pass the amount and the receiver's wallet
            $wallet->transfer($receiverWallet,$amount);
            return response()->json(['message' => 'Transfer successful']);
        } else {
            return response()->json(['message' => 'Insufficient balance'], 400);
        }
    }

    // Recharge Wallet (via UPI, Bank, Cards)
    public function recharge(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $amount = $request->input('amount');
        $wallet = Auth::user()->wallet;

        // Here you can integrate with third-party payment gateways (UPI, Bank, etc.)
        // For now, we simulate a recharge

        $wallet->deposit($amount);  // Credit the amount to the wallet
        return response()->json(['message' => 'Wallet recharged successfully', 'balance' => $wallet->balance]);
    }

    // Commission Deduction (Admin Only)
    public function deductCommission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'commission_percentage' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $commissionPercentage = $request->input('commission_percentage');
        $userWallet = Auth::user()->wallet;

        // Commission logic
        $commissionAmount = ($userWallet->balance * $commissionPercentage) / 100;
        $userWallet->withdraw($commissionAmount);  // debit to withdraw

        return response()->json(['message' => 'Commission deducted successfully', 'balance' => $userWallet->balance]);
    }
}
