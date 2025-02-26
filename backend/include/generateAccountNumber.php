<?php
function generateAccountNumber($userId) {
    $apiKey = "YOUR_API_KEY"; // Replace with your Monnify API key
    $apiSecret = "YOUR_API_SECRET"; // Replace with your Monnify API secret
    $apiUrl = "https://sandbox.monnify.com/api/v1/virtual-account/generate"; // Monnify API endpoint

    // Set up cURL to call Monnify API
    $headers = [
        "Authorization: Bearer $apiKey",
        "Content-Type: application/json"
    ];

    // Request body to create a virtual account
    $data = [
        "currencyCode" => "NGN",  // Currency for the account
        "accountReference" => "user_$userId",  // Unique account reference for each user
        "accountName" => "User $userId",  // Name for the account
        "callbackUrl" => "http://your-website.com/callback"  // Replace with your callback URL
    ];

    // Initialize cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    // Execute cURL and get the response
    $response = curl_exec($ch);

    // Handle response or errors
    if(curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    } else {
        $responseData = json_decode($response, true);
        if($responseData['responseCode'] == '00') {
            // Successfully created virtual account, store the account number
            $accountNumber = $responseData['data']['accountNumber'];
            return $accountNumber;
        } else {
            echo "Error creating virtual account: " . $responseData['message'];
        }
    }

    curl_close($ch);
}
?>
