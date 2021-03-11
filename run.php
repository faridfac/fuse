<?php
error_reporting(0);
while (true) {
  again:
  $users = randomuser();
  $pecah = explode("@",$users['email']);
  $domain = "gmailwe.com";
  $mail = $pecah[0];
  $email = $mail."@".$domain;

  $regis = regis($email);
  $response = $regis['response'];
  echo "[*] Registration $email -> ";
  if (preg_match('/subscriber_created/i', $response)) {
    echo "OK\n";
    $conf = $regis['confirmation_links']['email'];
    echo "[*] Send Mail -> ";
    $execute = sendmail($email, $conf);
    echo "$execute\n";

    $try = 1;
    do{
      echo "[*] Getting Verify Email... ";
      $getmail = get_mail($domain, $mail);

      if(preg_match('/Confirm your email/i', $getmail)){
        echo "success\n";
        $ea = 1;
        $link = get_between($getmail, 'radius: 3px" href="', '" rel="nofollow');
        if(preg_match('/token/i', $link)){
          $pid = get_between($link, 'pid%3D', '%26token');
          $token = get_between($link, 'token%3D', '%26return_url');
          echo "[*] PID -> $pid | Token -> $token\n";
          $verify = verify($pid, $token);
          echo "$verify";
          echo "[*] Sleep 15 seconds\n\n";
          sleep(15);
        } elseif ($ea == "5") {
          echo "Skip \n";
          goto again;
        } else {
          $ea++;
        }

        $success = 1;
      } elseif ($try == "5") {
        echo "Skip \n";
        goto again;
      }else {
        $success = 0;
        $try++;
      }
    }while($success==0);
  } else {
    echo "FAIL\n";
  }
}

function regis($email){
  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, 'https://app.referralhero.com/widget/MF453dc29c6d/post');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, '{"test_mode":false,"check_status":false,"one_click_signup":false,"email":"'.$email.'","uuid":"MF453dc29c6d","host":"https://fuse.cash/","referrer":"eda0c277","source":"","campaign":"","require_leaderboard":false}');
  curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

  $headers = array();
  $headers[] = 'Authority: app.referralhero.com';
  $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.190 Safari/537.36';
  $headers[] = 'Content-Type: application/json';
  $headers[] = 'Accept: */*';
  $headers[] = 'Origin: https://fuse.cash';
  $headers[] = 'Sec-Fetch-Site: cross-site';
  $headers[] = 'Sec-Fetch-Mode: cors';
  $headers[] = 'Sec-Fetch-Dest: empty';
  $headers[] = 'Referer: https://fuse.cash/';
  $headers[] = 'Accept-Language: id-ID,id;q=0.9';
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  $result = curl_exec($ch);
  $js = json_decode($result, true);
  return $js;
}

function sendmail($email, $conf){
  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, 'https://wm6c1kxpcl.execute-api.us-west-1.amazonaws.com/prod/pm_confirmation_email');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, '{"TEMPLATE_ID":22559817,"CAMPAIGN_ID":"MF453dc29c6d-confirmation_email","TO":"'.$email.'","FROM":"VcoCWmPpaK/3ueJkdGPOfE6RuNWqotgtLmQ4a4MUZvc=--PuT9M4x55/ozO0rMoNRr/w==","REPLY_TO":"mOP2dEoRwGTcsnduLI3a4A==--7MBGyO8mkTSr6bGaNvsRZw==","SUBSTITUTION_DATA":{"name":null,"confirmation_link":"'.$conf.'"}}');
  curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

  $headers = array();
  $headers[] = 'Authority: wm6c1kxpcl.execute-api.us-west-1.amazonaws.com';
  $headers[] = 'Authorization: VqNKVY6XYqXG9oZC9WS1Aot07zJFxZchgAr/hE6C6cyQo2Ho4gP3xOfbKuSaQ5PMeMnFAsfkdTeYfEUFlWyHnxlR+Ftlo9dTfDzGUHwOkFNmT5wPuzVHDwG1oFrpoUDyAQxqxLMUUVpkJCK6WXf7ZA==--hrzvXpGX0jaas3AszhfFgA==';
  $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.190 Safari/537.36';
  $headers[] = 'Content-Type: application/json';
  $headers[] = 'Accept: */*';
  $headers[] = 'Origin: https://fuse.cash';
  $headers[] = 'Sec-Fetch-Site: cross-site';
  $headers[] = 'Sec-Fetch-Mode: cors';
  $headers[] = 'Sec-Fetch-Dest: empty';
  $headers[] = 'Referer: https://fuse.cash/';
  $headers[] = 'Accept-Language: id-ID,id;q=0.9';
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  $result = curl_exec($ch);
  $js = json_decode($result, true);
  $response = $js['success'];
  if ($response == true) {
    return "OK";
  } else {
    return "Fail";
  }
}

function randomuser(){
    randomuser:
    $randomuser = file_get_contents('https://wirkel.com/data.php?qty=1&domain=xsingles.site');
    $json = json_decode($randomuser, true);
    $data = $json['result']['0'];
    return $data;
}

function get_mail($domain, $email){
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://generator.email/$domain/$email",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_HTTPHEADER => array(
      "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3",
      "accept-encoding: gzip, deflate, br",
      "upgrade-insecure-requests: 1",
      "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.86 Safari/537.36",
      "cookie: _ga=GA1.2.659238676.1567004853; _gid=GA1.2.273162863.1569757277; embx=%5B%22$email%40$domain%22%2C%22hcycl%40nongzaa.tk%22%5D; _gat=1; io=io=tIcarRGNgwqgtn40OGr4; surl=$domain%2F$email",
      "Content-Type: text/plain"
    ),
  ));

  $result = curl_exec($curl);
  return $result;
}

function get_between($string, $start, $end){
   $string = " ".$string;
   $ini = strpos($string,$start);
   if ($ini == 0) return "";
   $ini += strlen($start);
   $len = strpos($string,$end,$ini) - $ini;
   return substr($string,$ini,$len);
}

function verify($pid, $token){
  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, 'https://app.referralhero.com/confirm_email?pid='.$pid.'&token='.$token.'&return_url=https%3A%2F%2Ffuse.cash%2F');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
  curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

  $headers = array();
  $headers[] = 'Authority: app.referralhero.com';
  $headers[] = 'Upgrade-Insecure-Requests: 1';
  $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.190 Safari/537.36';
  $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
  $headers[] = 'Sec-Fetch-Site: none';
  $headers[] = 'Sec-Fetch-Mode: navigate';
  $headers[] = 'Sec-Fetch-User: ?1';
  $headers[] = 'Sec-Fetch-Dest: document';
  $headers[] = 'Accept-Language: id-ID,id;q=0.9';
  $headers[] = 'Cookie: __cfduid=d6acd317a58821fd2aa46cc61dd2919181615448418; _waiting_session=cC9GUUhwcUNqMDhwd1JsT3VKU01Oank2eks3amtXSFJVUG1TcktFdnNrREdMSUk2dTNodWxNSjd6VnBzd1Y2MzdLZThxZlVzZFVVMStCYTliMUdSQUVSa3BoUnpEQWVnR1dMK1NxQUMyRkQ1Z1R5VlBOemFtdWpWWE1WVHdOaWpNanJpeHVEUklWVXJ3U0tuek9CcnJBPT0tLW00SDl2QmlFamRSeHFrMk1NMjZ3cWc9PQ^%^3D^%^3D--f363d70dcc70ced886e207b65c34f21843a7451e';
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  $result = curl_exec($ch);
  if(preg_match('/Your email has been confirmed/i', $result)){
    return "[*] Confirmation Success\n";
  } else {
    return "[*] Confirmation Failed\n";
  }
}
?>
