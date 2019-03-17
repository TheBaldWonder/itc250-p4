<?php
require '../inc_0700/config_inc.php';
spl_autoload_register('MyAutoLoader::NamespaceLoader');
$config->metaRobots = 'no index, no follow';

//main function
//check that session has an ID
if(isset($_GET['id']) && (int)$_GET['id'] > 0){ 
    get_header(); //execute header
    showFeeds(); //execute function below
    get_footer();  //execute footer
    
}else{
    
    myRedirect(VIRTUAL_PATH . "RSS/subcategories.php"); //show feed categories

}
//end main function


// show feeds
function showFeeds(){ 
    //$expireAfterSeconds = 5 * 60; // check how long it has been
    $expireAfterSeconds = 2 * 6; // test setup
    $TimeStamp = time(); // intialize timestamp
    $cache = false; //initialize variable
    $myID = (int)$_GET['id'];   // get value of $myID
    
    startSession(); //start the session recording
    
    //if(!isset($_SESSION['Feeds'][$myID])){
    if(!isset($_SESSION['Feeds'])){
        $_SESSION['Feeds'] = [];
        echo "<p>Session variable set</p>";
    }
    else{
        $secondsInactive = time() - $_SESSION[$TimeStamp]->TimeStamp; //check time last timestamp retrieved.

        echo "<p>Session variable set</p>"; //test statement
        
        
        //if longer then 10 min, get new cache
        if($secondsInactive > $expireAfterSeconds){ //not seeing this. Why?
            //$_SESSION['Feeds'][$myID]->TimeStamp = time();
*********** //changed $myID to $TimeStamp here and elsewhere time is saved in $myID     ********************
            $_SESSION['Feeds'][$TimeStamp]->TimeStamp = time();
    //        echo 'session2 = ' . $_SESSION['Feeds'][$myID]->TimeStamp;
            $cache = false; // time is up 
            echo "entered here"; //test comment
        }
        else{
            $cache = true;      //cache is still valid
            echo "yep, saw this"; //test comment
        }       
    }
    
    //get new cache
    if($cache == false) {
    //if(!isset($_SESSION['Feeds'])) {
        
        //echo 'NEW CACHE';
        //$_SESSION['Feeds'][$myID]->TimeStamp = time(); //set time stamp
        
        $_SESSION['Feeds'][$TimeStamp]->TimeStamp = time(); //set time stamp
        //echo 'Feed Last Updated = ' . date('m/d/Y H:i:s', $_SESSION['Feeds'][$myID]->TimeStamp); //echo timestamp to visitor
        echo 'Feed Last Updated = ' . date('m/d/Y H:i:s', $_SESSION['Feeds'][$TimeStamp]->TimeStamp); //echo timestamp to visitor
        
        echo '<p>stuck here every time</p>';
        
        //get feed data:
        $sql = "select FeedURL from p4_feedURLs where FeedID=" . $myID;
        $result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));
                                  
        while($row = mysqli_fetch_assoc($result)){
            $Feeds[] = dbOut($row['FeedURL']);     
        }
        
        @mysqli_free_result($result);
        
        foreach($Feeds as $Feed){
            $response = file_get_contents($Feed);
            $xml = simplexml_load_string($response);
            foreach($xml->channel->item as $item){
                $myStory = new Story ($item->link, $item->title, strtotime($item->pubDate));
                global $myStories;
                $Stories[] = $myStory;
            }
        }
                
        usort($Stories, 'sort_objects_by_date');
        
        $_SESSION['Feeds'][] = array();
        $_SESSION['Feeds'][$myID] = new Feed($myID, $Stories, $TimeStamp);
                                           
    }
    else{ // just return last feed updated time
        //echo 'Feed Updated = ' . date('m/d/Y H:i:s', $_SESSION['Feeds'][$myID]->TimeStamp); //echo timestamp to visitor
        echo 'Feed Updated = ' . date('m/d/Y H:i:s', $_SESSION['Feeds'][$TimeStamp]->TimeStamp); //echo timestamp to visitor
        echo"Entered this statement"; //test statement
    //    echo 'Cache from = ' . $_SESSION['Feeds'][$myID]->TimeStamp . '   Current time = ' . time() . '    difference = ' . $secondsInactive . '   ';
    }
    
    
    //display content of feed, whether fresh cache, or stored cache
    foreach($_SESSION['Feeds'][$myID]->Stories as $story){
        echo '<a href="' . $story->Link . '"><h3>' . $story->Title . '</h3></a>';
    }
}


function sort_objects_by_date($a, $b){
    if ($a->PubDate == $b->PubDate){
        return 0;
    }
    
    return ($a->PubDate > $b->PubDate) ? -1 : 1;
}
class Story{
    public $Link = '';
    public $Title = '';
    public $PubDate = '';
    
    public function __construct($Link, $Title, $PubDate){
        $this->Link = $Link;
        $this->Title = $Title;   
        $this->PubDate = $PubDate;
    }
}
class Feed{ //feed class
    public $ID = 0;
    public $Stories = array();
    public $TimeStamp = '';
    
    public function __construct($ID, $Stories, $TimeStamp){
        $this->ID = $ID;
        $this->Stories = $Stories;
        $this->TimeStamp = $TimeStamp;
    }
}
?>
