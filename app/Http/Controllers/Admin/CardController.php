<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\AmazonCardAddJob;
use App\Models\AmazonOrderAccount;
use App\Models\CardDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class CardController extends Controller
{
    public $phpPath;
    public $artisanPath;
    public function __construct()
    {
        $this->phpPath = 'E:/Applications/laragon/bin/php/php8.2/php.exe';
        $this->artisanPath = 'E:/Applications/laragon/www/Laravel/63_cPanel_mail/artisan';
    }


    //card
    public function card()
    {
        return view("admin.pages.card.index");
    }


    /*
    =============================
        Amazon Order
    =============================
    */
    // addCard
    public function addCard(Request $request)
    {
        if($request->isMethod("POST")){
            $name = $request->name;
            $amazon_id = $request->amazon_id;
            $cards = explode("\n", $request->cards);

            if($amazon_id == "all"){
                $forLoopEnd = number_format(count($cards)/6, 0);
                for ($i=0; $i < $forLoopEnd; $i++) {
                    $sliceCardNumber = $i*6;
                    $amazonAccount = $i + 1;
                    $cards2 = array_slice($cards, $sliceCardNumber, 6);

                    // run job 
                    foreach ($cards2 as $key => $card) {
                        $month = isset(explode("|", $card)[1]) ? explode("|", $card)[1] : $request->month;
                        $year = isset(explode("|", $card)[2]) ? explode("|", $card)[2] : $request->year;
                        $card = isset(explode("|", $card)[0]) ? explode("|", $card)[0] : $card;

                        $command = "$this->phpPath \"$this->artisanPath\" app:amazon-card-add \"$name\" \"$card\" \"$month\" \"$year\" \"$amazonAccount\" 2>&1"; // php artisan app:amazon-card-add "step" "5415141515151515" 12 2027 1
                        shell_exec($command);
                    }
                }
            }else{
                $amazonAccount = $amazon_id + 1;
                foreach ($cards as $key => $card) {
                    $command = "$this->phpPath \"$this->artisanPath\" app:amazon-card-add \"$name\" \"$card\" \"$month\" \"$year\" \"$amazonAccount\" 2>&1";
                    shell_exec($command);
                }
            }
            
            return back();
        }

        $data = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19];

        return view("admin.pages.card.add", compact("data"));
    }

    // removeCard
    public function removeCard(Request $request)
    {
        $data = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19];
        $cards = CardDetails::latest()->get();

        return view("admin.pages.card.remove", compact("data", "cards"));
    }
    // removeCardDynamic
    public function removeCardDynamic(Request $request)
    {
        return response()->stream(function () use ($request) {
            $amazon_id = $request->amazon_id;
            $sr_no = 0;
            while ($amazon_id) {
                // check max id or not 
                if(env("PUPPETEER_MAX_AMAZON_ID") < $amazon_id){
                    echo "data: Process Complete\n\n";
                    ob_flush();
                    flush();
                    break;
                }

                $output = $this->runRemovalCode($amazon_id);
                $output = str_replace("Node.js script executed successfully!\n", "", $output);
                $pattern = '/\{\s+profile_id:\s*(.*?),\s+code:\s*(.*?),\s+card_details:\s+(.*?)\}/s';
                preg_match($pattern, $output, $matches);

                // If we find the profile_id in the output
                if (isset($matches[1])) {
                    $profile_id = $matches[1];
                    $code = $matches[2];
                    $card_details = $matches[3];
                    $new_profile = $profile_id - 1;
                    $sr_no = $sr_no + 1;

                    // card details 
                    $htmlCode = "<h2 class='profile-id'>Profile No: $new_profile</h2><p class='serial-no'>Serial No: $sr_no</p> $card_details";
                    $card = new CardDetails();
                    $card->details = $htmlCode;
                    $card->save();

                    echo "data: $htmlCode";
                    echo "\n\n\n\n\n\n\n\n";
                    ob_flush();
                    flush();

                    $amazon_id = $profile_id;
                    sleep(2);
                } else {
                    echo "data: MSG: ".$output." \n\n";
                    ob_flush();
                    flush();
                    break;
                }
            }
        }, 200, [
            'Content-Type'  => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection'    => 'keep-alive',
        ]);
    }
    // removeCardDynamic
    private function runRemovalCode($amazon_id)
    {
        $command = "$this->phpPath \"$this->artisanPath\" app:amazon-card-remove \"$amazon_id\" 2>&1";
        $output = shell_exec($command);
      
        return $output;
    }
    // removeCardClear
    public function removeCardClear()
    {
        CardDetails::truncate();
      
        return back()->with("msg", "Success");
    }
    // getCards
    public function getCards(Request $request)
    {
        if($request->isMethod("POST")){
            $cards = explode("\n", $request->cards);
            $cards = array_map('trim', $cards);
            $bank = $request->bank_name;
            $details = CardDetails::latest()->pluck("details");

            $filteredArray = [];
            $filteredCards = [];
            foreach ($details as $value) {
                $planCard = strip_tags($value);
                
                // Check if the planCard contains "Credit One Bank Visa"
                if (preg_match("/$bank/", $planCard)) {
                    $filteredArray[] = $planCard;

                    // Now check cards
                    foreach ($cards as $card) {
                        $lastFourDigits = substr($card, -4);
                        
                        // Corrected regex to match last 4 digits
                        if (preg_match("/$lastFourDigits/", $planCard)) {  
                            $filteredCards[] = $card;
                        }
                    }
                }
            }


            return back()->with(["filteredCards" => $filteredCards, "cards" => $request->cards, "bank_name" => $bank, "total_cards" => count($cards)]);
        }
        return view("admin.pages.card.get-cards");
    }

    

    // openBrowserCard
    public function openBrowserCard(Request $request)
    {
        $command = "$this->phpPath \"$this->artisanPath\" app:open-browser 2>&1";
        shell_exec($command);

        return back();
    }

    

    /*
    =============================
        Amazon Order
    =============================
    */
    public function amazonOrder(Request $request)
    {
        $accountEmails = AmazonOrderAccount::orderBy('id', 'ASC')->pluck("email");
        $accountPasswords = AmazonOrderAccount::orderBy('id', 'ASC')->pluck("password");
        $accountNames = AmazonOrderAccount::orderBy('id', 'ASC')->pluck("name");
        $accountCards = AmazonOrderAccount::orderBy('id', 'ASC')->pluck("card_number");

        if($request->isMethod("POST")){
            return response()->json(["emails" => $accountEmails, "passwords" => $accountPasswords, "names" => $accountNames, "cards" => $accountCards]);
        }
        
        return view("admin.pages.amazon.order", compact("accountNames", "accountEmails", "accountPasswords", "accountCards"));
    }
    public function amazonOrderSave(Request $request)
    {
        AmazonOrderAccount::truncate();

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

        // free book 
        $free_books = explode("\n", $request->free_books);
        $free_books = array_map('trim', $free_books);
        $randomBookKey = array_rand($free_books);
        $randomBookValue = $free_books[$randomBookKey];

        // add to cart
        $cart_items = explode("\n", $request->cart_items);
        $cart_items = array_map('trim', $cart_items);
        $randomKeys = array_rand($cart_items, 2);
        $randomCartValues = [$cart_items[$randomKeys[0]], $cart_items[$randomKeys[1]]];

        $month = $request->month;
        $year = $request->year;

        for ($i=0; $i < count($emails); $i++) {
            $randomAddressKey = array_rand($address);
            $randomAddressValue = $address[$randomAddressKey];

            $email = $emails[$i];
            $password = $passwords[$i];
            $name = $names[$i];
            $card = $cards[$i];
            $number = $randomAddressValue[4];
            $addres = $randomAddressValue[0];
            $city = $randomAddressValue[1];
            $state = $randomAddressValue[2];
            $zip = $randomAddressValue[3];

            $account = new AmazonOrderAccount();
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
            $account->cart1 = $randomCartValues[0];
            $account->cart2 = $randomCartValues[1];
            $account->free_book = $randomBookValue;
            $account->save();
        }

        return back()->with('success', 'Account successfully saved!');
    }
    public function amazonOrderSubmit(Request $request)
    {
        return response()->stream(function () {
            $account = AmazonOrderAccount::orderBy('id', 'ASC')->first();
            if(empty($account)){
                echo "data: [COMPLETE]\n\n";
                ob_flush();
                flush();
            }
            
            // int data  
            $email = $account->email;
            $password = $account->password;
            $cart1 = $account->cart1;
            $cart2 = $account->cart2;
            $free_book = $account->free_book;
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

            // Login 
            $loginAttempts = 0;
            $maxLoginAttempts = 10;
            while ($loginAttempts < $maxLoginAttempts) {
                $loginAttempts++;
                $loginData = $this->orderLogin($email, $password);
                $loginStatus = intval($loginData['status']);
                $loginMsg = $loginData['msg'];

                echo "data: Email:$email Trying:$loginAttempts Status:$loginStatus MSG:$loginMsg \n\n";
                ob_flush();
                flush();

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
                exit();
            }
            sleep(1);

            // add to cart 
            $addToCartData = $this->orderCart($cart1, $cart2);
            echo "data: ".$addToCartData['msg'] ." ID:$email". "\n\n";
            ob_flush();
            flush();
            sleep(1);

            // order free book
            $freeBookAttempts = 0;
            $maxLoginAttempts = 10;
            while ($freeBookAttempts < $maxLoginAttempts) {
                $freeBookAttempts++;
                $freeBookData = $this->orderFreeBook($free_book);
                $freeBookStatus = intval($freeBookData['status']);
                $freeBookMsg = $freeBookData['msg'];

                echo "data: Email:$email Trying:$freeBookAttempts MSG:$freeBookMsg \n\n";
                ob_flush();
                flush();

                // Exit loop if freeBook is successful
                if ($freeBookStatus === 1) {
                    break; 
                }
            }
            // free book faild 
            if (!$freeBookStatus == 1) {
                echo "data: Order free book \n\n";
                ob_flush();
                flush();
                exit();
            }

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
                exit();
            }

            // sleep 
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
                exit();
            }

            // sleep 
            sleep(1);

            // read 
            $bookReadData = $this->orderRead($free_book);
            echo "data: ".$bookReadData['msg'] ." ID:$email". "\n\n";
            ob_flush();
            flush();

            echo "data: [DONE]\n\n";
            ob_flush();
            flush();
            // remove this one 
            $account->delete();
            exit();
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
        ]);
    }

    // private  
    private function orderLogin($email, $password){
        $command = "$this->phpPath \"$this->artisanPath\" command:amazonorder-login \"$email\" \"$password\" 2>&1";
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
    private function orderCart($cart1, $cart2){
        $command = "$this->phpPath \"$this->artisanPath\" command:amazonorder-cart \"$cart1\" \"$cart2\" 2>&1";
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
    private function orderFreeBook($freebook){
        $command = "$this->phpPath \"$this->artisanPath\" command:amazonorder-order \"$freebook\" 2>&1";
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
        $command = "$this->phpPath \"$this->artisanPath\" command:amazonorder-address \"$country\" \"$full_name\" \"$number\" \"$address\" \"$city\" \"$state\" \"$zip_code\" 2>&1";
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
        $command = "$this->phpPath \"$this->artisanPath\" command:amazonorder-card \"$full_name\" \"$card_number\" \"$month\" \"$year\" 2>&1";
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
    private function orderRead($freebook){
        $command = "$this->phpPath \"$this->artisanPath\" command:amazonorder-read \"$freebook\" 2>&1";
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
}
