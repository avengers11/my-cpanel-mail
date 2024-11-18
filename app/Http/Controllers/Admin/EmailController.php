<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CpanelEmailAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;
use ZanySoft\Cpanel\Cpanel;

class EmailController extends Controller
{
    //email
    public function email()
    {
        $user = auth()->user();

        // cPanel API URL
        $cpanelUrl = 'https://ultra.whiteregistrar.com:2083/execute/Email/add_pop';

        // Your cPanel username and password (preferably store in .env)
        $cpanelUsername = $user->cpanle_username;
        $cpanelPassword = $user->cpanle_password; // You can also use the API token here

        // Data for the new email account
        $email = 'mr12'; // Email username (e.g., newuser@yourdomain.com)
        $password = 'paqssworqq3wd123'; // Email account password
        $domain = $user->domain; // Your domain name
        $quota = 256; // Optional: Email account quota in MB

        // Make the HTTP POST request using Basic Authentication (username and password)
        $response = Http::withBasicAuth($cpanelUsername, $cpanelPassword)
            ->asForm()
            ->post($cpanelUrl, [
                'email' => $email,
                'password' => $password,
                'domain' => $domain,
                'quota' => $quota,
            ]);

        // Check if the request was successful
        if ($response->successful()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Email account created successfully.',
                'data' => $response->json(),
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create email account.',
                'error' => $response->body(),
            ]);
        }

































        return "ss";

        $user = auth()->user();

        $emails = CpanelEmailAccount::latest()->where("user_id", $user->id)->paginate(20);
        if($user->role == "admin"){
            $emails = CpanelEmailAccount::latest()->paginate(20);
        }

        return view("admin.pages.email.index", compact('emails', 'user'));
    }

    // add
    public function add()
    {
        return view("admin.pages.email.add");
    }
    public function addSubmit(Request $request)
    {
        $emailAddress = $request->input('email') . "@" . auth()->user()->domain;
        $username = $request->input('email');
        $password = Str::random(rand(10, 12));

        // check 
        if(CpanelEmailAccount::where("email", $emailAddress)->exists()){
            return redirect(route("admin.email.index"))->with("error", "This email already exists!");
        }
        
        $this->createEmailAccount($username, $password);
        $this->forwardEmail($emailAddress);
        
        $emailAccount = new CpanelEmailAccount();
        $emailAccount->email = $emailAddress;
        $emailAccount->forward_email = $request->input('forward_email');
        $emailAccount->password = $password;
        $emailAccount->user_id = auth()->user()->id;
        $emailAccount->save();
        
        return redirect(route("admin.email.index"))->with("success", "Your email successfully created!");
    }
    // delete
    public function delete(CpanelEmailAccount $email)
    {
        $email->delete();
        return redirect(route("admin.email.index"))->with("success", "Your email successfully deleted!");
    }

    // common 
    private function createEmailAccount($username, $password, $quota = 250)
    {
        $user = Auth::user();

        return $this->makeRequest([
            'cpanel_jsonapi_version' => '2',
            'cpanel_jsonapi_module' => 'Email',
            'cpanel_jsonapi_func' => 'addpop',
            'domain' => $user->domain,
            'email' => $username,
            'password' => $password,
            'quota' => $quota,
        ]);
    }
    private function forwardEmail($email)
    {
        $user = Auth::user();

        return $this->makeRequest([
            'cpanel_jsonapi_version' => '2',
            'cpanel_jsonapi_module' => 'Email',
            'cpanel_jsonapi_func' => 'addforward',
            'domain' => $user->domain,
            'email' => $email,
            'fwdopt' => 'fwd',
            'fwdemail' => $user->forward_email,
        ]);
    }
    private function makeRequest($queryParams = [])
    {
        $user = Auth::user();
        $url = "{$user->cpanle_url}/json-api/cpanel";
        $fullUrl = $url . '?' . http_build_query($queryParams);

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0 Mobile/15E148 Safari/604.1', // Add a user-agent for identification
            ])->withBasicAuth($user->cpanle_username, $user->cpanle_password)
            ->timeout(10) // Set a timeout to prevent hanging requests
            ->get($fullUrl);

            if ($response->successful()) {
                return $response->json();
            }

            // Log and return the response in case of failure
            return [
                'success' => false,
                'error' => $response->body(),
            ];
        } catch (\Exception $e) {
            // Log exception details for debugging
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
    private function deleteEmailAccount($email)
    {
        $user = Auth::user();

        return $this->makeRequest([
            'cpanel_jsonapi_version' => 2,
            'cpanel_jsonapi_module' => 'Email',
            'cpanel_jsonapi_func' => 'delpop',
            'domain' => $user->domain,
            'email' => $email,
        ]);
    }
    private function generateRandomEmail() {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $textLength = rand(5, 10); // Random length for the text part
        $textPart = '';
        for ($i = 0; $i < $textLength; $i++) {
            $textPart .= $characters[rand(0, strlen($characters) - 1)];
        }
        $numberPart = rand(100, 999);
        $randomEmail = $textPart . $numberPart;
        return strtolower($randomEmail);
    }

}
