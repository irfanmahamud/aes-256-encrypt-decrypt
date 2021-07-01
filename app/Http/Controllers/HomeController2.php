<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function decrypthome(){
        return view('dechome');
    }
    public function store(Request $request)
    {
        if ($request->hasFile('userFile') && $request->file('userFile')->isValid()) {
            define('AES_256_CBC', 'aes-256-cbc');
            $fileName=uniqid();
            $encryption_key=$request->userKey;
            //$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(AES_256_CBC));
            //$iv = openssl_random_pseudo_bytes($request->userIv);
            $secret_iv = 0;
            $iv = substr(hash('sha256', $secret_iv), 0, 16);

            Storage::disk('local')->put($fileName.'iv.txt', $iv);
            $inputFile=$request->file('userFile');
            $data = file_get_contents($inputFile);
            echo "Before encryption: $data\n";
            $encrypted = openssl_encrypt($data, AES_256_CBC, $encryption_key, 0, $iv);
            echo "Encrypted: $encrypted\n";

            Storage::disk('local')->put($fileName.'encrypted.txt', $encrypted);
            Session::put('encrypted_file_name',$fileName.'encrypted.txt');
            return redirect()->route('home')->with('message', 'Encryption Complete');
        }
    }

    public function retrive(Request $request)
    {
        if ($request->hasFile('userFile') && $request->file('userFile')->isValid()) {
            define('AES_256_CBC', 'aes-256-cbc');
            $fileName=uniqid();
            $encryption_key=$request->userKey;
            $file_to_decrypt=$request->file('userFile');
            $data = file_get_contents($file_to_decrypt);
            $encrypted = $data;
          //  dd(base64_decode($encrypted));
            //$iv=file_get_contents($request->file('userIvFile'));
            //$encrypted = $data . ':' . base64_encode($iv);
            $secret_iv = 0;
            $iv = substr(hash('sha256', $secret_iv), 0, 16);
            //$parts = explode(':', $encrypted);
            //dd($iv);
            //$decrypted = openssl_decrypt(base64_decode($encrypted), AES_256_CBC, $encryption_key, 0, $iv);  // cbc mcrypt_enc_get_modes_name
            $decrypted =  openssl_decrypt(base64_decode($encrypted), 'aes-256-ecb', $encryption_key, OPENSSL_PKCS1_PADDING); //ECB mode
            dd($decrypted);
            Storage::disk('local')->put($fileName.'decrypted.txt', $decrypted);
            Session::put('decrypted_file_name',$fileName.'decrypted.txt');
            return redirect()->route('dec-home')->with('message_decr', 'Decryption complete');
        }
    }
    public function download($fileName){
        Log::debug($fileName);
        return response()->download(storage_path('app/'.$fileName));
    }
    public function downloadIv($fileName){
        $fileName=str_replace('encrypted', 'iv', $fileName);
        return response()->download(storage_path('app/'.$fileName));
    }

    function decrypt($data, $key) {
        return openssl_decrypt(base64_decode($data), 'aes-128-ecb', $key, OPENSSL_PKCS1_PADDING);
    }



    public function encrypt(Request $request){
// DEFINE our cipher
        define('AES_256_CBC', 'aes-256-cbc');

// Generate a 256-bit encryption key
// This should be stored somewhere instead of recreating it each time
        $encryption_key = openssl_random_pseudo_bytes(32);

// Generate an initialization vector
// This *MUST* be available for decryption as well
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(AES_256_CBC));

// Create some data to encrypt
        $data = "Encrypt me, please!";
        echo "Before encryption: $data\n";

// Encrypt $data using aes-256-cbc cipher with the given encryption key and
// our initialization vector. The 0 gives us the default options, but can
// be changed to OPENSSL_RAW_DATA or OPENSSL_ZERO_PADDING
        $encrypted = openssl_encrypt($data, AES_256_CBC, $encryption_key, 0, $iv);
        echo "Encrypted: $encrypted\n";

// If we lose the $iv variable, we can't decrypt this, so:
// - $encrypted is already base64-encoded from openssl_encrypt
// - Append a separator that we know won't exist in base64, ":"
// - And then append a base64-encoded $iv
        $encrypted = $encrypted . ':' . base64_encode($iv);

// To decrypt, separate the encrypted data from the initialization vector ($iv).
        $parts = explode(':', $encrypted);
// $parts[0] = encrypted data
// $parts[1] = base-64 encoded initialization vector

// Don't forget to base64-decode the $iv before feeding it back to
//openssl_decrypt
        $decrypted = openssl_decrypt($parts[0], AES_256_CBC, $encryption_key, 0, base64_decode($parts[1]));
        echo "Decrypted: $decrypted\n";

    }
}
