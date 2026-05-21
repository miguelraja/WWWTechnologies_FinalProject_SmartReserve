<?php
    define('TEST_MODE', true);
    echo "<div style='font-family: Arial, sans-seriff; max-width: 600px, margin: 30px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; background: #fafafa;'>";
    echo "<h2 style='text-align: center; colo: #333;'>SmartReserve - Automated Test</h2>";
    echo "<hr style='border: 0; border-top: 1px solid #eee; margin-bottom: 20px;'>";

    $total_tests = 0;
    $passed_tests = 0;
    $failed_tests = 0;

    $total_tests ++;
    echo "<strong>Test 1: Security check on reserve.php (Unauthenticated user)</strong><br>";
    $url = "http://localhost/smartreserve/reserve.php?id=1";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    $response = curl_init($url) ? curl_exec($ch) : false;
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($http_code == 302 || strpos($response, 'Location: login.php') !== false || $response === false) {
        echo "<span style='color: green; font-weight: bold;'>[PASSED]</span> User without session was correctly blocked abd redirected.<br><br>";
        $passed_tests ++;
    } else {
        echo "<span style='color: red; font-weight: bold;'>[FAILED]</span> Unauthenticated user could access the page.<br><br>";
        $failed_tests ++;
    }


    $total_tests ++;
    echo "<strong>Test 2: Password Hashing Integrity</strong><br>";

    $test_password = "my_password123";
    $hash = password_hash($test_password, PASSWORD_BCRYPT);

    if (password_verify($test_password, $hash)) {
        echo "<span style='color: green; font-weight: bold;'>[PASSED]</span> Password hashing and verification systems match.<br><br>";
        $passed_tests ++;
    } else {
        echo "<span style='color: red; font-weight: bold;'>[FAILED]</span> Password verification algorithm does not work correctly.<br><br>";
        $failed_tests ++;
    }


    echo "<hr style='border: 0; border-top: 1px solid #eee; margin-top: 20px;'>";
    echo "<he style='text-align: center;'>Results: <br>There are $total_tests tests. <br>$passed_tests passed, $failed_tests failed.";

    if ($passed_tests == $total_tests) {
        echo "<div style='background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; text-align: center; font-weight: bold;'>System is secure and stable.</div>";    
    } else {
        echo "<div style='background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; text-align: center; font-weight: bold;'>Tests failed. Review the errors above.</div>";
    }

    echo "</div>";
?>