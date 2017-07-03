<?php

namespace jsantos\sendgrid;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class SendgridController extends Controller
{



  public function curl_request($request, $json_post_fields = null)
  {

    // Generate curl request
    $ch = curl_init();
    $url = 'https://api.sendgrid.com/v3/';
    $headers =  array("Content-Type: application/json", "Authorization: Bearer ".env('SENDGRID_API'));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_URL, $request);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    /* json post if we have it */
    if(strlen($json_post_fields) > 0){
      curl_setopt($ch, CURLOPT_POSTFIELDS, $json_post_fields);
    }

    $data = curl_exec($ch);
    if (curl_errno($ch)) {
      return curl_error($ch);
    } else {
      curl_close($ch);
      return $data;
    }

  }

  public function add_recipients($email,$first_name,$last_name,$list_id)
  {

    $url = 'https://api.sendgrid.com/v3/';
    /* specific from add recipients */
    $request =  $url.'contactdb/recipients';
    $params = array(array(
      'email' => $email,
      'first_name' => $first_name,
      'last_name' => $last_name
    ));
    $json_post_fields = json_encode($params);


    $result = $this->curl_request($request, $json_post_fields);
    $final = json_decode($result);

    if(isset($final->persisted_recipients) && count($final->persisted_recipients) > 0){
      foreach($final->persisted_recipients as $id){

        /* send to the list */
        $result = $this->add_to_list($list_id, $id);

      }
    }


  }

  public function add_to_list($list,$recipient_id){

    $url = 'https://api.sendgrid.com/v3/';

    $request =  $url.'contactdb/lists/"'.$list.'"/recipients/'.$recipient_id;
    $json_post_fields = '';
    $this->curl_request($request, $json_post_fields);
  }

}
