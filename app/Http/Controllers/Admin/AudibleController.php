<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AudibleOrderAccount;
use App\Models\DisableAccount;
use Illuminate\Http\Request;

class AudibleController extends Controller
{
    public $phpPath;
    public $artisanPath;
    public function __construct()
    {
        $this->phpPath = 'E:/Applications/laragon/bin/php/php8.2/php.exe';
        $this->artisanPath = 'E:/Applications/laragon/www/Laravel/63_cPanel_mail/artisan';
    }


    public function orderView(Request $request)
    {
        $accountEmails = AudibleOrderAccount::orderBy('id', 'ASC')->pluck("email");
        $accountPasswords = AudibleOrderAccount::orderBy('id', 'ASC')->pluck("password");
        $accountNames = AudibleOrderAccount::orderBy('id', 'ASC')->pluck("name");
        $accountCards = AudibleOrderAccount::orderBy('id', 'ASC')->pluck("card_number");
        $disables = DisableAccount::get();

        if($request->isMethod("POST")){
            return response()->json(["emails" => $accountEmails, "passwords" => $accountPasswords, "names" => $accountNames, "cards" => $accountCards]);
        }

        // $AudibleOrderAccount = AudibleOrderAccount::get();
        // $keywords = [
        //     "MacBook",
        //     "Samsung Galaxy",
        //     "PlayStation 5",
        //     "Xbox Series X",
        //     "Beats Headphones",
        //     "Kindle Paperwhite",
        //     "Oculus Quest",
        //     "Dyson Vacuum",
        //     "Instant Pot",
        //     "KitchenAid Mixer",
        //     "Stanley Cup (Trending tumbler)",
        //     "YETI Cooler",
        //     "Nike Sneakers",
        //     "Adidas Slides",
        //     "Fitbit",
        //     "GoPro Camera",
        //     "Echo Dot",
        //     "Ring Doorbell",
        //     "Fire TV Stick",
        //     "Roku Streaming Stick"
        // ];
        
        // foreach ($AudibleOrderAccount as $value) {
        //     // Assign a random keyword from the list
        //     $value->keyword = $keywords[array_rand($keywords)];
        //     $value->save();
        // }
        
        
        return view("admin.pages.audible.order", compact("accountNames", "accountEmails", "accountPasswords", "accountCards", "disables"));
    }
    public function orderSave(Request $request)
    {
        AudibleOrderAccount::truncate();

        // emails
        $emails = explode("\n", $request->emails);
        $emails = array_map('trim', $emails);

        // passwords
        $passwords = explode("\n", $request->passwords);
        $passwords = array_map('trim', $passwords);

        // names
        $names = explode("\n", $request->names);
        $names = array_map('trim', $names);

        // cards
        $cards = explode("\n", $request->cards);
        $cards = array_map('trim', $cards);

        // address
        $address = explode("\n", $request->address);
        $address = array_map('trim', $address);
        $address = explode("\n", $request->address);
        $address = array_map(function($line) {
            return array_map('trim', explode('|', $line));
        }, $address);

        $month = $request->month;
        $year = $request->year;

        

        for ($i=0; $i < count($emails); $i++) {
            // address 
            $randomAddressKey = array_rand($address);
            $randomAddressValue = $address[$randomAddressKey];

            // add to cart
            $keywords = explode("\n", $request->keyword);
            $keywords = array_map('trim', $keywords);
            $randomKeys = array_rand($keywords, 1);
            $cartKeywords = $keywords[$randomKeys];

            $email = $emails[$i];
            $password = $passwords[$i];
            $name = $names[$i];
            $card = $cards[$i];
            $number = $randomAddressValue[4];
            $addres = $randomAddressValue[0];
            $city = $randomAddressValue[1];
            $state = $randomAddressValue[2];
            $zip = $randomAddressValue[3];

            $account = new AudibleOrderAccount();
            $account->email = $email;
            $account->password = $password;
            $account->email = $email;
            $account->name = $name;
            $account->number = $number;
            $account->address = $addres;
            $account->city = $city;
            $account->state = $state;
            $account->zip_code = $zip;
            $account->card_number = $card;
            $account->city = $city;
            $account->month = $month;
            $account->year = $year;
            $account->keyword = $cartKeywords;
            $account->audible_book = $request->audible_book;
            $account->save();
        }

        return back()->with('success', 'Account successfully saved!');
    }

    //order
    public function orderProcess()
    {
        return response()->stream(function () {
            $account = AudibleOrderAccount::orderBy('id', 'ASC')->first();
            if(empty($account)){
                echo "data: [COMPLETE]\n\n";
                ob_flush();
                flush();
                exit();
            }
            
            // int data  
            $email = $account->email;
            $password = $account->password;
            $keyword = $account->keyword;
            $country = "US";
            $full_name = $account->name;
            $number = $account->number;
            $address = $account->address;
            $city = $account->city;
            $state = $account->state;
            $zip_code = $account->zip_code;
            $card_number = $account->card_number;
            $month = $account->month;
            $year = $account->year;
            $audible_book = $account->audible_book;

            // Login 
            $loginAttempts = 0;
            $maxLoginAttempts = 5;
            while ($loginAttempts < $maxLoginAttempts) {
                $loginAttempts++;
                $loginData = $this->orderLogin($email, $password);
                $loginStatus = intval($loginData['status']);
                $loginMsg = $loginData['msg'];

                echo "data: Email:$email Trying:$loginAttempts Status:$loginStatus MSG:$loginMsg \n\n";
                ob_flush();
                flush();

                // account status 
                if($loginStatus == 2){
                    sleep(1);
                    // submit acc 
                    $disable = new DisableAccount();
                    $disable->email = $email;
                    $disable->save();

                    $account->delete();

                    echo "data: [DONE]\n\n";
                    ob_flush();
                    flush();
                    exit();
                }
                
                // Exit loop if login is successful
                if ($loginStatus === 1) {
                    break; 
                }
            }
            // login faild 
            if (!$loginStatus == 1) {
                echo "data: Login faild \n\n";
                ob_flush();
                flush();

                // restart new 
                sleep(2);
                echo "data: [DONE]\n\n";
                ob_flush();
                flush();
                $account->delete();
                exit();
            }
            sleep(1);

            // add to cart 
            $addToCartData = $this->orderCart($keyword);
            echo "data: ".$addToCartData['msg'] ." ID:$email". "\n\n";
            ob_flush();
            flush();
            sleep(1);


            // address 
            $addressAttempts = 0;
            $maxLoginAttempts = 5;
            while ($addressAttempts < $maxLoginAttempts) {
                $addressAttempts++;
                $addressData = $this->orderAddress($country, $full_name, $number, $address, $city, $state, $zip_code);
                $addressStatus = intval($addressData['status']);
                $addressMsg = $addressData['msg'];

                echo "data: Email:$email Trying:$addressAttempts MSG:$addressMsg \n\n";
                ob_flush();
                flush();

                // Exit loop if address is successful
                if ($addressStatus === 1) {
                    break; 
                }
            }
            // address faild 
            if (!$addressStatus == 1) {
                echo "data: Adding address faild \n\n";
                ob_flush();
                flush();
                

                // restart new 
                sleep(2);
                echo "data: [DONE]\n\n";
                ob_flush();
                flush();
                $account->delete();
                exit();
            }
            sleep(1);

            // card 
            $cardAttempts = 0;
            $maxLoginAttempts = 5;
            while ($cardAttempts < $maxLoginAttempts) {
                $cardAttempts++;
                $cardData = $this->orderCard($full_name, $card_number, $month, $year);
                $cardStatus = intval($cardData['status']);
                $cardMsg = $cardData['msg'];

                echo "data: Email:$email Trying:$cardAttempts MSG:$cardMsg \n\n";
                ob_flush();
                flush();

                // Exit loop if card is successful
                if ($cardStatus === 1) {
                    break; 
                }
            }
            // card faild 
            if (!$cardStatus == 1) {
                echo "data: Adding card faild \n\n";
                ob_flush();
                flush();
                
                
                // restart new 
                sleep(2);
                echo "data: [DONE]\n\n";
                ob_flush();
                flush();
                $account->delete();
                exit();
            }
            sleep(1);

            // // order 
            $orderAttempts = 0;
            $maxLoginAttempts = 5;
            while ($orderAttempts < $maxLoginAttempts) {
                $orderAttempts++;
                $bookOrderData = $this->audibleOrder($audible_book, $card_number);
                $orderStatus = intval($bookOrderData['status']);
                $orderMsg = $bookOrderData['msg'];

                echo "data: Email:$email Trying:$orderAttempts MSG:$orderMsg \n\n";
                ob_flush();
                flush();

                // Exit loop if order is successful
                if ($orderStatus === 1) {
                    break; 
                }
            }
            // order faild 
            if (!$orderStatus == 1) {
                echo "data: Adding order faild \n\n";
                ob_flush();
                flush();
                
                
                // restart new 
                sleep(2);
                echo "data: [DONE]\n\n";
                ob_flush();
                flush();
                $account->delete();
                exit();
            }


            echo "data: [DONE]\n\n";
            ob_flush();
            flush();

            // remove this one 
            $account->delete();
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
        ]);
    }


    // private  
    private function orderLogin($email, $password){
        $command = "$this->phpPath \"$this->artisanPath\" command:audibleorder-login \"$email\" \"$password\" 2>&1";
        $output = shell_exec($command);
        $pattern = '/\{\s+status:\s*(.*?),\s+msg:\s*(.*?)}/s';
        preg_match($pattern, $output, $matches);
        if (isset($matches[1])) {
            $status = $matches[1];
            $msg = $matches[2];

            return [
                "status" => $status,
                "msg" => $this->sanitizeMessage($msg)
            ];
        }else{
            return [
                "status" => false,
                "msg" => "Element not found:Backend-".$output
            ];
        }
    }
    private function orderCart($keyword){
        $command = "$this->phpPath \"$this->artisanPath\" command:audibleorder-cart \"$keyword\" 2>&1";
        $output = shell_exec($command);
        $pattern = '/\{\s+status:\s*(.*?),\s+msg:\s*(.*?)}/s';
        preg_match($pattern, $output, $matches);
        if (isset($matches[1])) {
            $status = $matches[1];
            $msg = $matches[2];

            return [
                "status" => $status,
                "msg" => $this->sanitizeMessage($msg)
            ];
        }else{
            return [
                "status" => false,
                "msg" => "Element not found:Backend-".$output
            ];
        }
    }
    private function orderAddress($country, $full_name, $number, $address, $city, $state, $zip_code){
        $command = "$this->phpPath \"$this->artisanPath\" command:audibleorder-address \"$country\" \"$full_name\" \"$number\" \"$address\" \"$city\" \"$state\" \"$zip_code\" 2>&1";
        $output = shell_exec($command);
        $pattern = '/\{\s+status:\s*(.*?),\s+msg:\s*(.*?)}/s';
        preg_match($pattern, $output, $matches);
        if (isset($matches[1])) {
            $status = $matches[1];
            $msg = $matches[2];
            return [
                "status" => $status,
                "msg" => $this->sanitizeMessage($msg)
            ];
        }else{
            return [
                "status" => false,
                "msg" => "Element not found:Backend-".$output
            ];
        }
    }
    private function orderCard($full_name, $card_number, $month, $year){
        $command = "$this->phpPath \"$this->artisanPath\" command:audibleorder-card \"$full_name\" \"$card_number\" \"$month\" \"$year\" 2>&1";
        $output = shell_exec($command);
        $pattern = '/\{\s+status:\s*(.*?),\s+msg:\s*(.*?)}/s';
        preg_match($pattern, $output, $matches);
        if (isset($matches[1])) {
            $status = $matches[1];
            $msg = $matches[2];
            return [
                "status" => $status,
                "msg" => $this->sanitizeMessage($msg)
            ];
        }else{
            return [
                "status" => false,
                "msg" => "Element not found:Backend-".$output
            ];
        }
    }
    private function audibleOrder($audible_book, $card_number){
        \Log::info($card_number);

        $command = "$this->phpPath \"$this->artisanPath\" command:audible-order \"$audible_book\" \"$card_number\" 2>&1";
        $output = shell_exec($command);
        $pattern = '/\{\s+status:\s*(.*?),\s+msg:\s*(.*?)}/s';
        preg_match($pattern, $output, $matches);
        if (isset($matches[1])) {
            $status = $matches[1];
            $msg = $matches[2];
            return [
                "status" => $status,
                "msg" => $this->sanitizeMessage($msg)
            ];
        }else{
            return [
                "status" => false,
                "msg" => "Element not found:Backend-".$output
            ];
        }
    }
    private function sanitizeMessage($msg)
    {
        $msg = trim($msg, '\'');
        return $msg;
    }


    // orderClear
    public function orderClear()
    {
        DisableAccount::truncate();
        return back()->with("msg", "Success");
    }
}
