<?php
class UNI
{
    public static function get($opt)
    {
        $arr = array();
        // Assuming $GLOBALS['connect'] is your database connection
        $ALLROW = mysqli_query($GLOBALS['connect'], $opt);
        if ($ALLROW) {
            while ($row = mysqli_fetch_array($ALLROW, MYSQLI_ASSOC)) {
                $arr[] = $row;
            }
        } else {
            // Handle error if query fails
            echo "Error: " . mysqli_error($GLOBALS['connect']);
        }
        return $arr;
    }
}

$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $ret = UNI::get("SELECT id, name, date FROM test");
    // $ret = array('id' => 1, 'name' => 'new', 'date' => '2022.05.16');
    echo json_encode($ret);
}
?>
