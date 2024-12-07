<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CpanelEmailAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Lib\HmailAPI;

class EmailController extends Controller
{
    //email
    public function email()
    {
        $user = auth()->user();

        // $emails = CpanelEmailAccount::latest()->where("user_id", $user->id)->paginate(20);
        // if($user->role == "admin"){
        //     $emails = CpanelEmailAccount::latest()->paginate(20);
        // }

        // $emails = HmailAPI::listEmails()['data'];

        // return $emails;

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
        $password = $request->input('password');
        $quota = (int)$request->input('quota');

        // check 
        if(CpanelEmailAccount::where("email", $emailAddress)->exists()){
            return redirect(route("admin.email.index"))->with("error", "This email already exists!");
        }
        
        //create email account on cpanel
        $cpanelResponse = HmailAPI::addEmail($emailAddress, $password, $quota);
        if(isset($cpanelResponse->status) && $cpanelResponse->status && isset($cpanelResponse->data)){
            $emailAccount = new CpanelEmailAccount();
            $emailAccount->email = $emailAddress;
            $emailAccount->password = $password;
            $emailAccount->user_id = auth()->user()->id;
            $emailAccount->save();
            
            return redirect(route("admin.email.index"))->with("success", "Your email successfully created!");
        }else{
            return redirect(route("admin.email.index"))->with('error','Error: Unable to create email on cpanel. Check your settings.');
        }

        
    }
    // delete
    public function delete(CpanelEmailAccount $email)
    {
        if(HmailAPI::deleteEmail($email->email)) {
            $email->delete();

            return redirect(route("admin.email.index"))->with("success", "Your email successfully deleted!");
        }
        return redirect(route("admin.email.index"))->with('error','Error: Unable to delete email on cpanel. Check your settings.');
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
