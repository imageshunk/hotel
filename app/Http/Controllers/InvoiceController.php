<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Konekt\PdfInvoice\InvoicePrinter;
use App\Order;
use App\User;
use App\Item;
use App\CheckIn;
use App\Room;
use App\Package;
use App\Utility;
use App\Restuarent;
use App\Setting;
use PDF;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class InvoiceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function order(Request $request){
        if(!auth()->user()->hasAnyRole(Role::all())){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        
        $id = $request->input('id');
        $setting = Setting::first();
        $order = Order::find($id);
        $guest = User::find($order->guest_id);
        $checkin = CheckIn::where('guest_id', $guest->id)->whereNotIn('previous_checkin', ['1'])->first();
        $room = Room::find($checkin->room_id);
        $package = Package::find($checkin->package);

        if($order->status != 'delivered'){
            return redirect()->back()->with('error', 'Invoice Not Available');
        }else{
            $guest = User::find($order->guest_id);
            $orders = Order::where([
                ['guest_id', $order->guest_id],
                ['payment_status', 'Paid']
            ])
            ->whereNotIn('status', ['not available', 'cancelled'])
            ->get();

            $invoice = new InvoicePrinter('a4', $setting->currency, 'en');
            /* Header settings */
            $invoice->setLogo("storage/images/".$setting->logo);   //logo image path
            $invoice->setColor("#000000");      // pdf color scheme
            if($request->input('type') == 'Receipt'){
                $invoice->setType("Receipt");
            }else{
                $invoice->setType("Invoice");
            }    // Invoice Type
            $invoice->setReference("INV-".$checkin->id);   // Reference
            $invoice->setDate(date('M dS ,Y',time()));   //Billing Date
            $invoice->setTime(date('h:i:s A',time()));   //Billing Time
            $invoice->setFrom(array($setting->hotel_name,$setting->address,$setting->contact,$setting->website));
            $invoice->setTo(array($guest->title.' '.$guest->name,$guest->email,$guest->mobile,$guest->country));
            
            $room_price = $checkin->total / $checkin->nights;
            $invoice->addItem($package->package_name,false,$checkin->nights.' Night(s)',false,$room_price,false,$checkin->total);
            $option_total = 0;
            if($checkin->utilities){
                $utilities = explode(",",$checkin->utilities);
                foreach ($utilities as $utility) {
                    $option = Utility::where('id', $utility)->first();
                    $invoice->addItem($option->utility,false,1,false,$option->price,false,$option->price);
                    $option_total += $option->price;
                }
            }
            $total = 0;
            foreach($orders as $order){
                $item = Item::find($order->item_id);
                $invoice->addItem($item->item_name,false,$order->quantity,false,$item->item_price,false,$order->amount);
                $total += $order->amount;
            }

            $total_amount = $total + $checkin->total + $option_total;
            $invoice->addTotal("Total",$total_amount);

            // 
            $number = $total_amount;
            $no = round($number);
            $point = round($number - $no, 2) * 100;
            $hundred = null;
            $digits_1 = strlen($no);
            $i = 0;
            $str = array();
            $words = array('0' => 'Zero', '1' => 'One', '2' => 'Two',
                '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
                '7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
                '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
                '13' => 'Thirteen', '14' => 'Fourteen',
                '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
                '18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty',
                '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
                '60' => 'Sixty', '70' => 'Seventy',
                '80' => 'Eighty', '90' => 'Ninety');
            $digits = array('', 'Hundred', 'Thousand', 'Hundred', 'Crore');
            while ($i < $digits_1) {
                $divider = ($i == 2) ? 10 : 100;
                $number = floor($no % $divider);
                $no = floor($no / $divider);
                $i += ($divider == 10) ? 1 : 2;
                if ($number) {
                    $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                    $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                    $str [] = ($number < 21) ? $words[$number] .
                        " " . $digits[$counter] . " " . $hundred
                        :
                        $words[floor($number / 10) * 10]
                        . " " . $words[$number % 10] . " "
                        . $digits[$counter] . " " . $hundred;
                } else $str[] = null;
            }
            $str = array_reverse($str);
            $result = implode('', $str);
            $points = ($point) ?
                "." . $words[$point / 10] . " " . 
                    $words[$point = $point % 10] : '';
            if($points){
                $invoice->addMethod($result ." Dollars & " . $points . " Cents");
            }else{
                $invoice->addMethod($result . $setting->currency);
            }
            // 
            
            // $invoice->addBadge("Paid");
            if($request->input('payment_type')!=''){
                $invoice->addBadge($request->input('payment_type'));
            }
            $invoice->addParagraph("For Banking Purposes:");
            $invoice->addParagraph("Bank Name: $setting->bank_name <br>Account Name: $setting->account_name <br>Account Number: $setting->account_no <br>Branch Name: $setting->branch_name <br>SWIFT CODE: $setting->code");

            $invoice->addParagraph("<br><br><br><br>");
            $invoice->addParagraph("____________________                                                                                                                                                      ___________________________");
            $invoice->addParagraph(" Customer Signature                                                                                                                                                         For $setting->hotel_name");
            
            $invoice->setFooternote("Thank you for staying with us");
            
            $invoice->render('invoice.pdf','I');
            /* I => Display on browser, D => Force Download, F => local path save, S => return document as string */
        }
    }

    public function booking(Request $request){
        if(!auth()->user()->hasAnyRole(Role::all())){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }

        $id = $request->input('id');
        $setting = Setting::first();
        $checkin = CheckIn::find($id);
        $guest = User::find($checkin->guest_id);
        $room = Room::find($checkin->room_id);
        $package = Package::find($checkin->package);
        
        $orders = Order::where([
            ['guest_id', $checkin->guest_id],
            ['payment_status', 'Paid'],
            ['status', 'delivered'],
        ])
        ->whereNotIn('status', ['rejected', 'cancelled'])
        ->get();

        // $pdf = PDF::loadView('invoices.booking', compact(['setting', 'checkin', 'guest', 'room', 'package', 'orders']));
        // return $pdf->stream('invoice.pdf');

        $invoice = new InvoicePrinter('a4', $setting->currency, 'en');
        /* Header settings */
        $invoice->setLogo("storage/images/".$setting->logo);   //logo image path
        $invoice->setColor("#000000");      // pdf color scheme
        if($request->input('type') == 'Receipt'){
            $invoice->setType("Receipt");
        }else{
            $invoice->setType("Invoice");
        }    // Invoice Type
        $invoice->setReference("INV-".$checkin->id);   // Reference
        $invoice->setDate(date('M dS ,Y',time()));   //Billing Date
        $invoice->setTime(date('h:i:s A',time()));   //Billing Time
        $invoice->setFrom(array($setting->hotel_name,$setting->address,$setting->contact,$setting->website));
        $invoice->setTo(array($guest->title.' '.$guest->name,$guest->email,$guest->mobile,$guest->country));
        
        $room_price = $checkin->total / $checkin->nights;
        $invoice->addItem($package->package_name,false,$checkin->nights.' Night(s)',false,$room_price,false,$checkin->total);
        $option_total = 0;
        if($checkin->utilities){
            $utilities = explode(",",$checkin->utilities);
            foreach ($utilities as $utility) {
                $option = Utility::where('id', $utility)->first();
                $invoice->addItem($option->utility,false,1,false,$option->price,false,$option->price);
                $option_total += $option->price;
            }
        }
        $total = 0;
        foreach($orders as $order){
            $item = Item::find($order->item_id);
            $invoice->addItem($item->item_name,false,$order->quantity,false,$item->item_price,false,$order->amount);
            $total += $order->amount;
        }

        $total_amount = $total + $checkin->total + $option_total;
        $invoice->addTotal("Total",$total_amount);

        // 
        $number = $total_amount;
        $no = round($number);
        $point = round($number - $no, 2) * 100;
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array('0' => '', '1' => 'One', '2' => 'Two',
            '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
            '7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
            '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
            '13' => 'Thirteen', '14' => 'Fourteen',
            '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
            '18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty',
            '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
            '60' => 'Sixty', '70' => 'Seventy',
            '80' => 'Eighty', '90' => 'Ninety');
        $digits = array('', 'Hundred', 'Thousand', 'Hundred', 'Crore');
        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number] .
                    " " . $digits[$counter]  . " " . $hundred
                    :
                    $words[floor($number / 10) * 10]
                    . " " . $words[$number % 10] . " "
                    . $digits[$counter] . " " . $hundred;
            } else $str[] = null;
        }
        $str = array_reverse($str);
        $result = implode('', $str);
        $points = ($point) ?
            "." . $words[$point / 10] . " " . 
                $words[$point = $point % 10] : '';
        if($points){
            $invoice->addMethod($result ." Dollars & " . $points . " Cents");
        }else{
            $invoice->addMethod($result . $setting->currency);
        }
        // 
        
        // $invoice->addBadge("Paid");
        if($request->input('payment_type')!=''){
            $invoice->addBadge($request->input('payment_type'));
        }
        $invoice->addParagraph("For Banking Purposes:");
        $invoice->addParagraph("Bank Name: $setting->bank_name <br>Account Name: $setting->account_name <br>Account Number: $setting->account_no <br>Branch Name: $setting->branch_name <br>SWIFT CODE: $setting->code");

        $invoice->addParagraph("<br><br><br>");
        $invoice->addParagraph("_______________________                                                                                                                             ___________________________");
        $invoice->addParagraph("Customer Signature                                                                                                                                                         For $setting->hotel_name");
            
        $invoice->setFooternote("Thank you for staying with us");
        
        $invoice->render('invoice.pdf','I');
        /* I => Display on browser, D => Force Download, F => local path save, S => return document as string */
    }

    public function restuarent(Request $request){
        if(!auth()->user()->hasAnyRole(Role::all())){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        
        $id = $request->input('id');
        $setting = Setting::first();
        $order = Restuarent::find($id);
        $guest = User::find($order->guest_id);

        if($order->status != 'delivered'){
            return redirect()->back()->with('error', 'Invoice Not Available');
        }else{
            $orders = Restuarent::where([
                ['guest_id', $order->guest_id],
                ['payment_status', 'Paid']
            ])
            ->whereNotIn('status', ['not available', 'cancelled'])
            ->get();

            $invoice = new InvoicePrinter('a4', $setting->currency, 'en');
            /* Header settings */
            $invoice->setLogo("storage/images/".$setting->logo);   //logo image path
            $invoice->setColor("#000000");      // pdf color scheme
            if($request->input('type') == 'Receipt'){
                $invoice->setType("Receipt");
            }else{
                $invoice->setType("Invoice");
            }    // Invoice Type
            $invoice->setReference("INV-".$order->id);   // Reference
            $invoice->setDate(date('M dS ,Y',time()));   //Billing Date
            $invoice->setTime(date('h:i:s A',time()));   //Billing Time
            $invoice->setFrom(array($setting->hotel_name,$setting->address,$setting->contact,$setting->website));
            $invoice->setTo(array($guest->title.' '.$guest->name,$guest->email,$guest->mobile,$guest->country));
            
            $total = 0;
            foreach($orders as $order){
                $item = Item::find($order->item_id);
                $invoice->addItem($item->item_name,false,$order->quantity,false,$item->item_price,false,$order->amount);
                $total += $order->amount;
            }
            
            $invoice->addTotal("Total",$total);
            
            // 
            $number = $total;
            $no = round($number);
            $point = round($number - $no, 2) * 100;
            $hundred = null;
            $digits_1 = strlen($no);
            $i = 0;
            $str = array();
            $words = array('0' => '', '1' => 'One', '2' => 'Two',
                '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
                '7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
                '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
                '13' => 'Thirteen', '14' => 'Fourteen',
                '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
                '18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty',
                '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
                '60' => 'Sixty', '70' => 'Seventy',
                '80' => 'Eighty', '90' => 'Ninety');
            $digits = array('', 'Hundred', 'Thousand', 'Hundred', 'Crore');
            while ($i < $digits_1) {
                $divider = ($i == 2) ? 10 : 100;
                $number = floor($no % $divider);
                $no = floor($no / $divider);
                $i += ($divider == 10) ? 1 : 2;
                if ($number) {
                    $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                    $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                    $str [] = ($number < 21) ? $words[$number] .
                        " " . $digits[$counter] . " " . $hundred
                        :
                        $words[floor($number / 10) * 10]
                        . " " . $words[$number % 10] . " "
                        . $digits[$counter] . " " . $hundred;
                } else $str[] = null;
            }
            $str = array_reverse($str);
            $result = implode('', $str);
            $points = ($point) ?
                "." . $words[$point / 10] . " " . 
                    $words[$point = $point % 10] : '';
            if($points){
                $invoice->addMethod($result ." Dollars & " . $points . " Cents");
            }else{
                $invoice->addMethod($result . $setting->currency);
            }
            // 
            
            // $invoice->addBadge("Paid");
            if($request->input('payment_type')!=''){
                $invoice->addBadge($request->input('payment_type'));
            }
            $invoice->addParagraph("For Banking Purposes:");
            $invoice->addParagraph("Bank Name: $setting->bank_name <br>Account Name: $setting->account_name <br>Account Number: $setting->account_no <br>Branch Name: $setting->branch_name <br>SWIFT CODE: $setting->code");

            $invoice->addParagraph("<br><br><br>");
            $invoice->addParagraph("____________________                                                                                                                                                    ___________________________");
            $invoice->addParagraph(" Customer Signature                                                                                                                                                         For $setting->hotel_name");
            
            $invoice->setFooternote("Thank you for staying with us");
            
            $invoice->render('invoice.pdf','I');
            /* I => Display on browser, D => Force Download, F => local path save, S => return document as string */
        }
    }
}
