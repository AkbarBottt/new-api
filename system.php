<?php
// system-test.php
// White Box Testing REST Client PHP untuk NewsAPI.org

$baseUrl = "https://newsapi.org/v2/";
$apiKey  = "571c73b632fa4635863641064ba9f355"; // API Key kamu

function callNewsApi($endpoint, $params = []) {
    global $baseUrl, $apiKey;
    $url = $baseUrl . $endpoint . "?" . http_build_query(array_merge($params, ["apiKey" => $apiKey]));

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) throw new Exception("Curl error: " . curl_error($ch));
    curl_close($ch);

    if ($httpCode >= 400) throw new Exception("HTTP Error Code: " . $httpCode);

    return json_decode($response, true);
}

function printResult($testName, $result) {
    echo $testName . " => " . ($result ? "PASS ✅" : "FAIL ❌") . PHP_EOL;
}

// TEST 1 - Parameter valid
try {
    $response = callNewsApi("top-headlines", ["country" => "us"]);
    $result = isset($response["status"]) && $response["status"] === "ok" && !empty($response["articles"]);
    printResult("TEST 1 - Valid headlines request", $result);
} catch (Exception $e) {
    printResult("TEST 1 - Valid headlines request", false);
}

// TEST 2 - Parameter invalid
try {
    $response = callNewsApi("top-headlines", ["country" => "invalid_country_code"]);
    $result = isset($response["status"]) && $response["status"] === "error";
    printResult("TEST 2 - Invalid parameter handling", $result);
} catch (Exception $e) {
    printResult("TEST 2 - Invalid parameter handling", true);
}

// TEST 3 - API key salah
try {
    $invalidKey = "INVALID_KEY";
    $response = callNewsApi("everything", ["q" => "technology", "apiKey" => $invalidKey]);
    $result = isset($response["status"]) && $response["status"] === "error";
    printResult("TEST 3 - Invalid API key handling", $result);
} catch (Exception $e) {
    printResult("TEST 3 - Invalid API key handling", true);
}
?>
