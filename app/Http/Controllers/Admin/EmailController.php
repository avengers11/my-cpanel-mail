<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Lib\HmailAPI;
use App\Models\CpanelEmailAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Mews\Purifier\Facades\Purifier;
use Webklex\PHPIMAP\ClientManager;

class EmailController extends Controller
{
    //email
    public function email()
    {
        $user = auth()->user();

        $dbEmails = CpanelEmailAccount::latest()->get(['email', 'id', 'password'])->keyBy('email');
        $cpanelEmails = HmailAPI::listEmails()['data'];
        
        $emails = [];
        foreach ($cpanelEmails as $index=>$value) {
            $email = $value['email'];
            $emails[] = [
                "mtime" => $value['mtime'],
                "id" => $dbEmails[$email]->id ?? null,
                "email" => $email,
                "password" => $dbEmails[$email]->password ?? null,
                "created_at" => date("Y-m-d H:i:s", $value['mtime'])
            ];
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
    public function addForwardSubmit(Request $request)
    {
        $emailAddress = $request->input('email');
        $forwardEmailAddress = $request->input('email_forward');

        //create email account on cpanel
        $cpanelResponse = HmailAPI::addForward($emailAddress, $forwardEmailAddress);

        if(isset($cpanelResponse) && $cpanelResponse){
            return redirect(route("admin.email.index"))->with("success", "You are successfully add a new forward mail!");
        }else{
            return redirect(route("admin.email.index"))->with('error','Error: Unable to create email on cpanel. Check your settings.');
        }
    }

    // generate
    public function generate(Request $req)
    {
        $email = $req->email;
        $password = Str::random(rand(10, 12));
        if(HmailAPI::updateEmailPassword($email, $password)){
            if(CpanelEmailAccount::where('email', $email)->exists()){
                CpanelEmailAccount::where('email', $email)->delete();
            }
            $emailAccount = new CpanelEmailAccount();
            $emailAccount->email = $email;
            $emailAccount->password = $password;
            $emailAccount->user_id = auth()->user()->id;
            $emailAccount->save();

            // redirect(url("https://mail.masudrana.top?email=&password="));
            
            return redirect(route('admin.email.fetchEmails', ["email" => $email, "password" => $password]));
        }
        return redirect(route("admin.email.index"))->with('error','Error: Unable to delete email on cpanel. Check your settings.');
    }
    // delete
    public function delete(Request $req)
    {
        try {
            if($req->id != null){
                CpanelEmailAccount::find($req->id)->delete();
            }
            HmailAPI::deleteEmail($req->email);

            return redirect(route("admin.email.index"))->with("success", "Your email successfully deleted!");
        } catch (\Throwable $th) {
            return redirect(route("admin.email.index"))->with('error','Error: Unable to delete email on cpanel. Check your settings.');
        }
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

    //getEmails 
    public function fetchEmails(Request $request)
    {
        if ($request->isMethod('POST')) {
            // Get user-provided email and password
            $email    = $request->email;
            $password = $request->password;
        
            // Configure IMAP
            $client = (new ClientManager())->make([
                'host'          => 'masudrana.top',
                'port'          => 993,
                'encryption'    => 'ssl',
                'validate_cert' => false,
                'username'      => $email,
                'password'      => $password,
                'protocol'      => 'imap'
            ]);
        
            $client->connect();
            $folder = $client->getFolder('INBOX');
        
            // Fetch emails (ignore IMAP sorting)
            $messages = $folder->query()
                ->all()
                ->leaveUnread()
                ->limit(30)
                ->get();
        
            $emails = [];
        
            foreach ($messages as $message) {
                $date = $message->getDate();
                $timestamp = null;
        
                if ($date) {
                    $dateString = $date->toString();
                    $dateTime = \DateTime::createFromFormat(\DateTime::RFC2822, $dateString);
                    
                    if (!$dateTime) {
                        $dateTime = date_create($dateString);
                    }
        
                    if ($dateTime) {
                        $timestamp = $dateTime->getTimestamp();
                    }
                }
        
                // Decode subject
                $subject = $message->getSubject() ? mb_decode_mimeheader($message->getSubject()) : 'No Subject';

                // Get HTML body if available, otherwise text body
                $body = $message->hasHTMLBody() ? 
                $message->getHTMLBody() : 
                nl2br($message->getTextBody());
                $body = mb_convert_encoding($body, 'UTF-8', mb_detect_encoding($body));
                $cleanBody = Purifier::clean($body, [
                    'HTML.Allowed' => 'p,br,b,strong,i,em,u,a[href|title],img[src|alt],ul,ol,li',
                    'Attr.AllowedClasses' => [],
                    'URI.AllowedSchemes' => ['http', 'https', 'mailto'],
                    'URI.DisableExternalResources' => false
                ]);
                $cleanBody = preg_replace_callback(
                    '/src="cid:(.*?)"/i',
                    function($matches) use ($message) {
                        $attachment = $message->getAttachments()
                            ->where('content_id', $matches[1])
                            ->first();
                        return $attachment ? 'src="data:'.$attachment->getMimeType().';base64,'.base64_encode($attachment->getContent()).'"' : $matches[0];
                    },
                    $cleanBody
                );
        
                $emails[] = [
                    'id'        => $message->getUid(),
                    'subject'   => $subject,
                    'from'      => $message->getFrom()[0]->mail ?? 'Unknown',
                    'timestamp' => $timestamp,
                    'body'      => $cleanBody,
                    'unread'    => !$message->getFlags()->get('seen'),
                ];
            }
        
            // Sort by timestamp (newest first)
            usort($emails, function ($a, $b) {
                return ($b['timestamp'] ?? 0) <=> ($a['timestamp'] ?? 0);
            });
        
            // Format time ago
            foreach ($emails as &$email) {
                $email['date'] = $email['timestamp'] ? $this->formatTimeAgo($email['timestamp']) : 'Unknown';
                unset($email['timestamp']);
            }
        
            return response()->json($emails);
        }

        return view('admin.pages.email.inbox.email');
    }
    public function markAsRead($emailId, Request $req)
    {
        $client = (new ClientManager())->make([
            'host'          => 'masudrana.top',
            'port'          => 993,
            'encryption'    => 'ssl',
            'validate_cert' => false,
            'username'      => $req->email,
            'password'      => $req->password,
            'protocol'      => 'imap'
        ]);
    
        $client->connect();
        $folder = $client->getFolder('INBOX');
        $message = $folder->query()->getMessageByUid($emailId);
    
        if ($message) {
            $message->setFlag('Seen');
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false], 404);
    }
    private function formatTimeAgo($timestamp)
    {
        $now = time();
        $diff = $now - $timestamp;
    
        $minutes = intval($diff / 60);
        $hours = intval($diff / 3600);
        $days = intval($diff / 86400);
    
        if ($days > 0) {
            return $days."d ".($hours % 24)."h ".($minutes % 60)."m ago";
        } elseif ($hours > 0) {
            return $hours."h ".($minutes % 60)."m ago";
        } else {
            return "{$minutes}m ago";
        }
    }
}
