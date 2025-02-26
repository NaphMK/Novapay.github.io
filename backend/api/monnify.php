<?php
function generateAccountNumber($username, $email) {

    // Monnify API credentials (use your sandbox/live credentials)
    $apiKey = "MK_TEST_7JLY82NJRJ";
    $apiSecret = "KW1CXL8MFG13M4PKF3VL4C2ZDJNE9334";
    $apiUrl = "https://sandbox.monnify.com/api/v1/disbursements/wallet"; // For sandbox

   
    $authKey = base64_encode("$apiKey:$apiSecret");
    $serviceCode = 9255725004;
    // Step 1: Authenticate and get access token
    $tokenUrl = "https://sandbox.monnify.com/api/v1/auth/login";
    $headers = [
        "Authorization: Basic $authKey",
        "Content-Type: application/json"
    ];

    $ch = curl_init($tokenUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    curl_close($ch);

    $responseData = json_decode($response, true);
    error_log("Auth Response: " . var_export($responseData, true));

    if ($responseData['responseCode'] != '00') {
        error_log("Authentication failed with response: " . var_export($responseData, true)); 
        return false;
    }

    $accessToken = $responseData['responseBody']['accessToken'];

    // Step 2: Generate account number
    $data = [
        "walletReference" => "user_" . $username,
        "walletName" => "User $username Wallet",
        "currencyCode" => "NGN",
        "bvnDetails" => ["bvn"=>"22514407597", "bvnDateOfBirth"=>"2001-03-13"],
        "customerEmail" => $email,
        "customerName" => "User $username",
        "getAllAvailableBanks" => false,
        "preferredBanks" => ["035"] // Wema Bank Code
    ];

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $accessToken",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    curl_close($ch);
    file_put_contents("shhrrhh.txt", $response);

    $responseData = json_decode($response, true);
    error_log("Account Generation Response: " . var_export($responseData, true));

    if ($responseData['responseCode'] == '0') {
        return $responseData['responseBody']['accountNumber'];
    } else {
        error_log("Failed to generate account number. Response: " . var_export($responseData, true));
       
        return true;
    }

    
}