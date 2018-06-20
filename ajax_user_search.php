<?php
include("../../config/config.php");
include("../../includes/classes/User.php");

$query = $_POST['query'];
$userLoggedIn = $_POST['userLoggedIn'];

$names = explode(" ", $query);

//If query contains an underscore, assume user is searching for usernames
if(strpos($query, '_') !== false)
    $usersReturnedQuery = $conn->query("SELECT * FROM user_info WHERE user_name LIKE '$query%' AND user_closed='no' LIMIT 8");
//If there are two words, assume they are first and last names respectively
else if(count($names) == 2)
    $usersReturnedQuery = $conn->query("SELECT * FROM user_info WHERE (first_name LIKE '$names[0]%' AND last_name LIKE '$names[1]%') AND user_closed='no' LIMIT 8");
//If query has one word only, search first names or last names
else
    $usersReturnedQuery = $conn->query("SELECT * FROM user_info WHERE (first_name LIKE '$names[0]%' OR last_name LIKE '$names[0]%') AND user_closed='no' LIMIT 8");


if($query != ""){

    while($row = $usersReturnedQuery->fetch_assoc()) {
        $user = new User($conn, $userLoggedIn);

        if($row['user_name'] != $userLoggedIn)
            $mutual_friends = $user->getMutualFriends($row['user_name']) . " friends in common";
        else
            $mutual_friends = "";

        echo "<div class='resultDisplay'>
                <a href='" . $row['user_name'] . "' style='color: #1485BD'>
                    <div class='liveSearchProfilePic'>
                        <img src='" . $row['profile_img'] ."'>
                    </div>

                    <div class='liveSearchText'>
                        " . $row['first_name'] . " " . $row['last_name'] . "
                        <p>" . $row['user_name'] ."</p>
                        <p id='grey'>" . $mutual_friends ."</p>
                    </div>
                </a>
                </div>";

    }

}

?>
