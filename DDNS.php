<?php
function send_post($url,$headers,$post_data) {
    $curl = curl_init();
    //初始化
    curl_setopt($curl,CURLOPT_URL,$url);
    //设置url
    curl_setopt($curl,CURLOPT_HTTPAUTH,CURLAUTH_BASIC);
    //设置http验证方法
    curl_setopt($curl,CURLOPT_HEADER,0);
    //设置头信息
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
    //设置curl_exec获取的信息的返回方式
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl,CURLOPT_POST,1);
    //设置发送方式为post请求
    curl_setopt($curl,CURLOPT_POSTFIELDS,$post_data);
    //设置post的数据
    $result = curl_exec($curl);
    curl_close($curl);
    return $result;
}

$token = "YourTokenId,YourToken";  //填入DNSPod提供的token信息
$command = "nslookup Yourdomain";  //填入您的域名
exec($command,$output);
$ip = str_replace(PHP_EOL, '', file_get_contents("http://ip.cip.cc"));
$dnsdata = explode(": ",$output[5],2)[1];
if (!empty($ip) && ($ip != $dnsdata) && (!empty($dnsdata))) {
    $dnsdata = json_decode(send_post("https://dnsapi.cn/Record.Ddns",array(),"login_token=".$token."&format=json&domain=laoooo.cn&record_id=413951842&record_line_id=0&sub_domain=@&value=".$ip),true);
    $command = "systemctl restart nscd";
    exec($command);
    print_r($dnsdata);
}
if (is_array($dnsdata)) {
    echo 'Success flush the dns to '.$ip;
} else {
    echo 'No need in '.$ip;
}
?>