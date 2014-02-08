<?php

if ($_REQUEST['onlyLoggedInTeam'] === "true") {
    $onlyLoggedInTeam = true;
} else {
    $onlyLoggedInTeam = false;
}

$docRoot = $_SERVER['DOCUMENT_ROOT'];

// initialize $params as an empty array; fill it with the logged in team's number if they want to filter
$params = array();

require_once $docRoot . '/includes/setup-session.php';
require_once $docRoot . '/includes/db-connect.php';

$queryString = ('SELECT scouted_team, '
        . 'format(AVG(auto_goal_value + (auto_hot_goal * 5) + (auto_moved_to_alliance_zone * 5)), 1) AS auto_points, '
        . 'format(AVG((tele_received_assists * 10) + (tele_high_goals * 10) + tele_low_goals + (tele_truss_throws * 10) '
        . '+ (tele_truss_catches * 10)), 1) AS tele_points, '
        . 'format(AVG(auto_goal_value + (auto_hot_goal * 5) + (auto_moved_to_alliance_zone * 5) + (tele_received_assists * 10) + '
        . '(tele_high_goals * 10) + tele_low_goals + (tele_truss_throws * 10) + (tele_truss_catches * 10)), 1) AS total_points '
        . 'FROM `frc_match_data`');

//if the team wants to filter then do so
if ($onlyLoggedInTeam) {
    //add filter to query string
    $queryString .= ' WHERE scouting_team=?';

    //this needs to be passed as an argument to the sql query
    array_push($params, $teamNumber);
}

$queryString .= ' GROUP BY `scouted_team`';

try {
    $query = $db->prepare($queryString);
    $query->execute($params);
} catch (PDOException $e) {
    print_r($e->getMessage());
}

while ($results = $query->fetch(PDO::FETCH_ASSOC)) {
//write the table in PHP instead of HTML
    echo "<tr>";
    echo "<td><a href=\"/team/" . $results['scouted_team'] . "\">";
    echo $results['scouted_team'];
    echo "</a></td>";
    echo "<td>";
    echo $results['total_points'];
    echo "</td>";
    echo "<td>";
    echo $results['auto_points'];
    echo "</td>";
    echo "<td>";
    echo $results['tele_points'];
    echo "</td>";
    echo "</tr>";
}
?>