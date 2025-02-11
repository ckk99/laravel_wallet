<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PasswordOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    // User registration with OTP verification
    public function register(Request $request)
    {
        // Validate input data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // OTP generation and verification should go here
        // Send OTP logic (via email or SMS)

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);
        $token = $user->createToken('API Token')->plainTextToken;
        $user = User::find($user->id); // Find the user
        $user->assignRole('Sub-User');
        // Send verification code (OTP logic to be added)

        return response()->json(['message' => 'User registered successfully', 'token' => $token], 201);
    }

    // Login method
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json(['token' => $token]);
    }

    // Implement 2FA logic here for two-factor authentication
    // ...

    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Generate OTP
        $otp = rand(100000, 999999);

        // Store OTP in the database
        PasswordOtp::updateOrCreate(
            ['email' => $request->email],
            [
                'otp' => $otp,
                'expires_at' => Carbon::now()->addMinutes(10), // OTP expires in 10 minutes
            ]
        );

        // Send OTP via email
        Mail::raw("Your OTP for password reset is: $otp", function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Password Reset OTP');
        });

        return response()->json(['message' => 'OTP sent to your email.'], 200);
    }

    // Step 2: Verify OTP
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Check if OTP is valid
        $otpRecord = PasswordOtp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->first();

        if (!$otpRecord) {
            return response()->json(['error' => 'Invalid OTP.'], 400);
        }

        if (Carbon::now()->greaterThan($otpRecord->expires_at)) {
            return response()->json(['error' => 'OTP has expired.'], 400);
        }

        return response()->json(['message' => 'OTP verified successfully.'], 200);
    }

    // Step 3: Reset Password
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|numeric',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Check if OTP is valid
        $otpRecord = PasswordOtp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->first();

        if (!$otpRecord) {
            return response()->json(['error' => 'Invalid OTP.'], 400);
        }

        if (Carbon::now()->greaterThan($otpRecord->expires_at)) {
            return response()->json(['error' => 'OTP has expired.'], 400);
        }

        // Reset the user's password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the OTP record
        $otpRecord->delete();

        return response()->json(['message' => 'Password reset successfully.'], 200);
    }

    public function profile(Request $request)
    {
        
        return response()->json(Auth::user());
    }   

    public function logout(Request $request)
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            // Get the authenticated user
            $user = Auth::user();

            // Revoke all of the user's tokens
            $user->tokens->each(function ($token) {
                $token->delete();
            });

            return response()->json(['message' => 'Successfully logged out']);
        } else {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
    }
}

