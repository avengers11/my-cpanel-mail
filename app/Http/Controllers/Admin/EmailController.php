<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CpanelEmailAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class EmailController extends Controller
{
    //email
    public function email()
    {
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
        $password = "Mr100hunter";

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
        $this->deleteEmailAccount($email);

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
        return Http::withBasicAuth($user->cpanle_username, $user->cpanle_password)->get($fullUrl);
    }
    public function deleteEmailAccount($email)
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

}
