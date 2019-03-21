<?php
/**
 * feed.php loads and parse xml data from various RSS feeds
 *
 * 
 * @author Michael Rodgers <michael.rodgers312@gmail.com>
 * @version 2019-03-19
 * @link http://michaelrodgers.azurewebsites.net/wn19/RSS/feed.php
 * @
 * @see feed_add.php 
 * @see feed_edit.php 
 * @see config_inc.php
 * @todo none
 */
require '../inc_0700/config_inc.php';
spl_autoload_register('MyAutoLoader::NamespaceLoader');
$config->metaRobots = 'no index, no follow';

//check if page has been visited before
if(isset($_GET['id']) && (int)$_GET['id'] > 0){
    get_header();
    showFeeds();
    
    echo '
    <a href="feed_add.php" >Add To Feed</a><br />
    <a href="feed_edit.php" >Edit Feed</a><br />';
    get_footer();    
    
}else{ //go to subcategories
    
    myRedirect(VIRTUAL_PATH . "RSS/subcategories.php");
}

// feed function
function showFeeds(){ 
    startSession(); //start session
    $myID = (int)$_GET['id']; //store session ID
    $cached = false; // store cache status
    $TimeStamp = 0; // initialize variable
    $Stories = array(); //initiailize array
    
    // for session 'Feed', get stories
    if (isset($_SESSION['Feed'])){
        foreach($_SESSION['Feed'] as $feed){
            if ($feed->Page == $myID){
                $TimeStamp == $feed->TimeStamp;
                $Stories[] = $feed->Stories;
                $cached = true;
            }
        }
    }
    
    // if not cached, or time up, get new feeds from sources.
    if ($cached == false || (time() - $TimeStamp >= 10 * 60)){
        
        $sql = "select FeedURL from p4_feedURLs where FeedID=" . $myID;
        $result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));
                                  
        while($row = mysqli_fetch_assoc($result)){
            $URLs[] = dbOut($row['FeedURL']);     
        }
        
        @mysqli_free_result($result);
        
        foreach($URLs as $URL){
            $response = file_get_contents($URL);
            $xml = simplexml_load_string($response);
            foreach($xml->channel->item as $item){
                $myStory = new Story ($item->link, $item->title, strtotime($item->pubDate));
                $Stories[] = $myStory;
            }
        }
        // sort stories        
        usort($Stories, 'sort_objects_by_date');
        //save stories in session
        $_SESSION['Feed'][] = new Feed ($myID, time(), $Stories);
        echo 'New Session Started at:' . time() . '<br />';
    } else { // echo the already-stored data.
        echo 'Session data exists prior to page load.  Last Cache: ' . $TimeStamp . '<br />';
    }
    // return stories to webpage
    
    foreach($Stories as $story){
        echo '<a href="' . $story->Link . '"><h3>' . $story->Title . '</h3></a>';
    }
    
    # If querystring loaded, destroy session
    if (isset($_GET['destroy'])) {
        $_SESSION = ""; #happens inside session_eliminate(), but not if handling via file/cookie
        session_destroy();
    } else {# Show link to allow destuction of session
        echo '<a href="wn19/RSS/feed.php?destroy=true">Destroy Session</a><br />';
    }
}
 
function sort_objects_by_date($a, $b){
    if ($a->PubDate == $b->PubDate){
        return 0;
    }
    return ($a->PubDate > $b->PubDate) ? -1 : 1;
}

//class Story
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

// class Feed
class Feed{
    public $Page = 0;
    public $TimeStamp = '';
    public $Stories = array();
    
    public function __construct($Page, $TimeStamp, $Stories){
        $this->Page = $Page;
        $this->TimeStamp = $TimeStamp;
        $this->Stories = $Stories;
    }
}
?>
