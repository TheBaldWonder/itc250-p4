<?php
require '../inc_0700/config_inc.php';
spl_autoload_register('MyAutoLoader::NamespaceLoader');
$config->metaRobots = 'no index, no follow';


if(isset($_GET['id']) && (int)$_GET['id'] > 0){
    get_header();
    showFeeds();
    get_footer();    
    
}else{
    
    myRedirect(VIRTUAL_PATH . "RSS/subcategories.php");
}

function showFeeds(){ 
    $expireAfterSeconds = 5 * 60;
    $cache = false;
    $myID = (int)$_GET['id'];
    
    startSession();
    
    if(isset($_SESSION['Feeds'][$myID])){
        $cache = true;
        $secondsInactive = time() - $_SESSION[$myID]->TimeStamp;
        
        echo 'Cache from = ' . $_SESSION['Feeds'][$myID]->TimeStamp . '   Current time = ' . time() . '    difference = ' . $secondsInactive . '   ';
        
        if($secondsInactive > $expireAfterSeconds){
            $_SESSION['Feeds'][$myID]->TimeStamp = time();
            echo 'session2 = ' . $_SESSION['Feeds'][$myID]->TimeStamp;
            $cache = false;
        }
    }
 
    if(!isset($_SESSION['Feeds'][$myID]) || $cache == false) {
        echo 'NEW CACHE';
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
        
        $_SESSION['Feeds'] = array();
        $_SESSION['Feeds'][$myID] = new Feed($myID, $Stories, time());
                                           
    }
    
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

class Feed{
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
