<?php 
    session_start();
    include ('../db_connection.php');
    
    function validationAndDecodeIdToken($idToken) {
        $clientId = '175487461829-um8ubpj71oi097ug21komlb88f52qa5p.apps.googleusercontent.com'; // Your ClientID
        $googleApiUrl = 'https://oauth2.googleapis.com/tokeninfo?id_token=' . $idToken;
    
        $response = file_get_contents($googleApiUrl);
        $userInfo = json_decode($response, true);
    
        if (isset($userInfo['aud']) && $userInfo['aud'] === $clientId) {
            return $userInfo; // Returns user info if valid
        } else {
            return false; // Invalid token or client ID
        }
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        error_log("POST request received"); // Log that a POST request was received
        if (isset($_POST['id_token'])) {
            $idToken = $_POST['id_token'];
            error_log("Received ID token: " . $idToken); // Log the ID token
        
            // Validate the Id token
            $userInfo = validationAndDecodeIdToken($idToken);
            $_SESSION["userInfo"] = $userInfo;

            if ($userInfo) {
                $first_name = $userInfo['given_name'];
                $last_name = $userInfo['family_name'];
                $email = $userInfo['email'];
                $_SESSION["ReceivedEmail"] = $email;
            
                // Check if the user already exists
                $checkQuery = "SELECT * FROM Client WHERE Email = ? AND Status = 0";
                $stmtCheck = $conn->prepare($checkQuery);
                $stmtCheck->bind_param("s", $email);
            
                if ($stmtCheck->execute()) {
                    $checkResult = $stmtCheck->get_result();
                    $rowCheck = $checkResult->fetch_array(MYSQLI_ASSOC);
                    
                    if ($rowCheck) {
                        // User already exists
                        $_SESSION['Client_id'] = $rowCheck['Client_id'];
                        //header('Location: ../Home/Home.php'); // Redirect to home page
                        exit();
                    } else {
                        // Prepare the insert query
                        $query1 = "INSERT INTO Client (First_name, Lastname, Email, Status) VALUES (?, ?, ?, 0)";
                        $stmtInsert = $conn->prepare($query1);
                        $stmtInsert->bind_param("sss", $first_name, $last_name, $email);
            
                        // Execute the insert query
                        if ($stmtInsert->execute()) {
                            // Prepare the select query to get the newly inserted user
                            $querySelect = "SELECT * FROM Client WHERE Email = ? AND Status = '0'";
                            $stmtSelect = $conn->prepare($querySelect);
                            $stmtSelect->bind_param("s", $email);
            
                            if ($stmtSelect->execute()) {
                                $resultSelect = $stmtSelect->get_result();
                                $rowSelect = $resultSelect->fetch_assoc();
                                $_SESSION['Client_id'] = $rowSelect['Client_id'];
                                //header('Location: ../Home/Home.php'); // Redirect to home page
                                exit();
                            } else {
                                echo "Error selecting data: " . $stmtSelect->error;
                            }
                        } else {
                            echo "Error inserting data: " . $stmtInsert->error;
                        }
                    }
                } else {
                    echo "Error executing check query: " . $stmtCheck->error;
                }
            
                // Close statements
                $stmtCheck->close();
                if (isset($stmtInsert)) {
                    $stmtInsert->close();
                }
                if (isset($stmtSelect)) {
                    $stmtSelect->close();
                }
            } else {
                echo "Failed to decode ID token";
            }
        }
    }
?>