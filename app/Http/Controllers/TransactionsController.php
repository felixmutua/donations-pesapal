<?php


namespace App\Http\Controllers;


use App\DonationOrder;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{

    public function index(Request $request)
    {

        $token = $params = NULL;
        $consumer_key = \config::CONSUMER_KEY;
        $consumer_secret = \config::CONSUMER_SECRET;
        $signature_method = new \OAuthSignatureMethod_HMAC_SHA1();
        $pesapalUrl = \config::PESAPAL_URL;

        //get form details<br>
        $amount = $request->amount;
        $amount = number_format($amount, 2);//format amount to 2 decimal places
        $desc = $request->fundraising_event;
        $type = \config::MERCHANT;
        $unique_id = floor(time() - 999999999);
        $reference = $unique_id;
        $first_name = $request->name;
        $last_name = '';
        $email = $request->email;
        $msisdn = $request->phoneNumber;

        $donation_order = new DonationOrder();
        $donation_order->donor_name = $first_name;
        $donation_order->donor_email = $email;
        $donation_order->msisdn = $msisdn;
        $donation_order->order_id = $reference;
        $donation_order->amount = $amount;
        $donation_order->status = \config::PENDING;
        $donation_order->save();


        $callback_url = \config::CALLBACK_URL; //redirect url, the page that will handle the response from pesapal.
        $post_xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?><PesapalDirectOrderInfo xmlns:xsi=\"http://www.w3.org/2001/XMLSchemainstance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" Amount=\"" . $amount . "\" Description=\"" . $desc . "\" Type=\"" . $type . "\" Reference=\"" . $reference . "\" FirstName=\"" . $first_name . "\" LastName=\"" . $last_name . "\" Email=\"" . $email . "\" PhoneNumber=\"" . $msisdn . "\" xmlns=\"http://www.pesapal.com\" />";
        $post_xml = htmlentities($post_xml);

        $consumer = new \OAuthConsumer($consumer_key, $consumer_secret);
        //post transaction to pesapal
        $iframe_src = \OAuthRequest::from_consumer_and_token($consumer, $token, "GET",
            $pesapalUrl, $params);
        $iframe_src->set_parameter("oauth_callback", $callback_url);
        $iframe_src->set_parameter("pesapal_request_data", $post_xml);
        $iframe_src->sign_request($signature_method, $consumer, $token);

        return $iframe_src;

    }

    public function redirect(Request $request)
    {

        $donorInvoice = DonationOrder::query()->where('order_id', $request->pesapal_merchant_reference)->get()->first();
        $donorInvoice->tracking_id = $request->pesapal_transaction_tracking_id;
        $donorInvoice->update();
        return view('redirect');
    }

    public function QueryPaymentStatus(Request $request)
    {
        $pesapalNotification = $request->pesapal_notification_type;
        $pesapalTrackingId = $request->pesapal_transaction_tracking_id;
        $pesapal_merchant_reference = $request->pesapal_merchant_reference;
        $token = $params = NULL;
        $consumer_key = \config::CONSUMER_KEY;
        $consumer_secret = \config::CONSUMER_SECRET;
        $signature_method = new \OAuthSignatureMethod_HMAC_SHA1();
        $statusrequestAPI = \config::QUERY_PAYMENT_URL;

        if ($pesapalNotification == "CHANGE" && $pesapalTrackingId != '') {
            $token = $params = NULL;
            $consumer = new \OAuthConsumer($consumer_key, $consumer_secret);
            $signature_method = new \OAuthSignatureMethod_HMAC_SHA1();

            //get transaction status
            $request_status = \OAuthRequest::from_consumer_and_token($consumer, $token, "GET", $statusrequestAPI, $params);
            $request_status->set_parameter("pesapal_merchant_reference", $pesapal_merchant_reference);
            $request_status->set_parameter("pesapal_transaction_tracking_id", $pesapalTrackingId);
            $request_status->sign_request($signature_method, $consumer, $token);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $request_status);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            if (defined('CURL_PROXY_REQUIRED')) if (CURL_PROXY_REQUIRED == 'True') {
                $proxy_tunnel_flag = (defined('CURL_PROXY_TUNNEL_FLAG') && strtoupper(CURL_PROXY_TUNNEL_FLAG) == 'FALSE') ? false : true;
                curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, $proxy_tunnel_flag);
                curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
                curl_setopt($ch, CURLOPT_PROXY, \config::CURL_PROXY_SERVER_DETAILS);
            }

            $response = curl_exec($ch);
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $raw_header = substr($response, 0, $header_size - 4);
            $headerArray = explode("\r\n\r\n", $raw_header);
            $header = $headerArray[count($headerArray) - 1];

            //transaction status
            $elements = preg_split("/=/", substr($response, $header_size));
            $status = $elements[1];

            curl_close($ch);

            $donorInvoice = DonationOrder::query()->where('tracking_id', $pesapalTrackingId)->get()->first();
            $donorInvoice->status = $status;
            $donorInvoice->update();

            if ($donorInvoice && $status != "PENDING") {
                $resp = "pesapal_notification_type=$pesapalNotification&pesapal_transaction_tracking_id=
                    $pesapalTrackingId&pesapal_merchant_reference=$pesapal_merchant_reference";
                ob_start();
                echo $resp;
                ob_flush();
                exit;
            }
        }
    }

    public function fetchPayments(Request  $request){

    }

}
