<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Data_User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response as Res;

class DataUserAPI extends Controller
{
    public function register(Request $request)
    {
        // Validasi input
        try {
            $validator = Validator::make($request->all(), [
                'name_user' => 'required|string|max:255',
                'phone_user' => 'required|string|max:15',
                'email_user' => 'required|string|email|max:255|unique:data_user,email_user',
                'password' => 'required|string|min:8',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validator->errors()
                ], 401);
            }

            // Membuat data user baru
            $user = Data_User::create([
                'name_user' => $request->name_user,
                'phone_user' => $request->phone_user,
                'email_user' => $request->email_user,
                'password' => Hash::make($request->password), // Hash password
            ]);

            return response()->json([
                'message' => 'User registered successfully',
                'user' => $user,
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        // Validasi input
        try {
            $validator = Validator::make($request->all(), [
                'email_user' => 'required|string|email|max:255',
                'password' => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 401);
            }

            // Attempt to log the user in
            $credentials = [
                'email_user' => $request->email_user,
                'password' => $request->password,
            ];

            $user = Data_User::where('email_user', $request->email_user)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['error' => 'Invalid email or password'], 401);
            }

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'user' => $user,
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


    public function forgotPassword(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'email_user' => 'required|string|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Mencari user berdasarkan email
        $user = Data_User::where('email_user', $request->email_user)->first();

        if (!$user) {
            return response()->json(['error' => 'Email not found'], 404);
        }

        // Membuat token reset password
        $token = Str::random(60);

        // Simpan token ke tabel password_resets
        DB::table('password_resets')->insert([
            'email_user' => $request->email_user,
            'token' => $token,
            'created_at' => now(),
        ]);

        // Kirim email dengan token reset password (gunakan mailer Anda)
        Mail::send('emails.password_reset', ['token' => $token], function ($message) use ($request) {
            $message->to($request->email_user);
            $message->subject('Reset Password Notification');
        });

        return response()->json([
            'message' => 'Password reset link sent to your email',
        ]);
    }

    public function getAllUsers()
    {
        try {
            // Mendapatkan semua data user
            $users = Data_User::all();
            $usersWithTokens = [];

            foreach ($users as $user) {
                // Mendapatkan personal access tokens terkait dengan user
                $tokens = $user->tokens;

                // Jika user memiliki minimal satu token, tambahkan user beserta token ke array
                if ($tokens->isNotEmpty()) {
                    foreach ($tokens as $token) {
                        // Pisahkan setiap user dan token ke dalam array terpisah
                        $usersWithTokens[] = [
                            'user' => [
                                'id' => $user->id,
                                'name' => $user->name_user,
                                'phone' => $user->phone_user,
                                'email' => $user->email_user,
                                'password' => $user->password,
                                'token' => $token->token,
                                // tambahkan kolom lainnya jika perlu
                            ],
                        ];
                    }
                } else {
                    // Jika user tidak memiliki token, tambahkan user tanpa token ke array
                    $usersWithTokens[] = [
                        'user' => [
                            'id' => $user->id,
                            'name' => $user->name_user,
                            'phone' => $user->phone_user,
                            'password' => $user->pssword,
                            // tambahkan kolom lainnya jika perlu
                        ],
                        'token' => null,
                    ];
                }
            }

            return response()->json([
                'message' => 'Successfully retrieved all users with tokens',
                'users' => $usersWithTokens,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve users with tokens',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function get_my_profile(Request $request)
    {
        try {
            $user_id = $request->user()->id;
            $res = Data_User::find($user_id);
            return response()->json([
                'status' => 'success',
                'message' => 'response fetched',
                'data' => $res
            ], Res::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'something wrong. Please contact admin',
            ], Res::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function editProfile(Request $request)
{
    // Validasi input
    $validator = Validator::make($request->all(), [
        'name_user' => 'required|string|max:255',
        'phone_user' => 'required|string|max:15',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation error',
            'errors' => $validator->errors()
        ], 401);
    }

    try {
        // Retrieve the authenticated user
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Update user name and phone number
        $user->name_user = $request->name_user;
        $user->phone_user = $request->phone_user;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully',
            'user' => $user
        ], Res::HTTP_OK);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Failed to update profile',
            'error' => $e->getMessage()
        ], Res::HTTP_INTERNAL_SERVER_ERROR);
    }
}

    public function uploadProfileImage(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:data_user,id',
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 401);
        }

        try {
            // Find user by ID
            $user = Data_User::find($request->user_id);

            // Store the image
            if ($request->hasFile('profile_image')) {
                $image = $request->file('profile_image');
                $path = $image->store('profile_images', 'public');
                $user->profile_image = $path;
                $user->save();
            }

            return response()->json([
                'status' => true,
                'message' => 'Profile image uploaded successfully',
                'user' => $user
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to upload profile image',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getAllUsersNoToken()
    {
        try {
            // Mendapatkan semua data user
            $users = Data_User::all();

            return response()->json([
                'message' => 'Successfully retrieved all users',
                'users' => $users,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve users',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

}


