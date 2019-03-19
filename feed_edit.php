<?php

/**
 * feed_edit.php allows users to edit the database and edit RSS feeds to use with feed.php
 *
 * @author Michael Rodgers <michael.rodgers312@gmail.com>
 * @version 2019-03-19
 * @link http://michaelrodgers.azurewebsites.net/wn19/RSS/feed.php
 * @
 * @see feed.php 
 * @see config_inc.php
 * @todo none
 */

require '../inc_0700/config_inc.php';
get_header();
    
    echo '
    <a href="feed_add.php" >Add To Feed</a><br />
    <a href="feed_edit.php" >Edit Feed</a><br />
    <h3 align="center">Edit RSS Feed</h3>';

if (isset($_POST['update'])){
    $iConn = IDB::conn();
    $sql = sprintf("UPDATE p4_feedURLs set FeedURL='%s' WHERE UrlID=%d",$_POST['newURL'],(int)$_POST['UrlID']);
 	
	mysqli_query($iConn,$sql) or die(trigger_error(mysqli_error($iConn), E_USER_ERROR));
	
	//feedback success or failure of insert
	if (mysqli_affected_rows($iConn) > 0){
        echo 'This feed has been successfully updated!';
    } else {
        echo 'Something went wrong.  Data not updated!';
    }
    
} else if (isset($_POST['edit'])){ 
	echo '
	<form action="' . VIRTUAL_PATH . '/RSS/feed_edit.php" method="post" onsubmit="return checkForm(this);">
	<table align="center">
		<tr>
			<td align="right">URL: </td>
			<td>
				<input type="url" name="newURL" value="' . $_POST['FeedURL'] . '" />
                <input type="hidden" name="UrlID" value="' . $_POST['UrlID'] . '" />
			</td>
		</tr>
	';
	echo '
	   <tr>
	   		<td align="center" colspan="2">
	   			<input type="submit" value="Update Feed" />
                <input type="hidden" name="update" value="feedback" />
	       </td>
	   </tr>
	</table>    
	</form>
	';  
        
} else if (isset($_POST['cat'])){
        echo '
        <form action="' . VIRTUAL_PATH . '/RSS/feed_edit.php" method="post" onsubmit="return checkForm(this);">
        <table align="center">
            <tr>
                <td align="right" style="padding-right: 1em;">Select URL:  </td>
                <td>
                ';
                
                $sql = "SELECT * FROM p4_feedURLs WHERE FeedID='{$_POST['FeedID']}'";
                $result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));
                while($row = mysqli_fetch_assoc($result)){
        
                    echo '
                    <input type="radio" name="FeedURL" value="' . $row['FeedURL'] . '" />' . dbOut($row['FeedURL']) . '<br />
                    <input type="hidden" name="UrlID" value="' . $row['UrlID'] . '" />';
                }
                
        echo '
                </td>
            </tr>
	   		<td align="center" colspan="2">
	   			<input type="submit" value="Edit Feed" />
                <input type="hidden" name="edit" value="feedback" />
	   		</td>
	   	</tr>
        </table>    
        </form>
        ';
        
        /*
        $iConn = IDB::conn();  
        $FeedURL = strval($_POST['FeedURL']);
        $sql = "INSERT INTO p4_feedURLs (FeedID, FeedURL) VALUES ('{$_POST['FeedID']}', '$FeedURL')";

        @mysqli_query($iConn,$sql) or die(trigger_error(mysqli_error($iConn), E_USER_ERROR));  # insert is done here

        # feedback success or failure of insert
        if (mysqli_affected_rows($iConn) > 0){
            feedback("Feed Added!", "notice");
        }else{
            feedback("Feed NOT Added!", "error");
        }
        */
        
} else { //show form - provide feedback

        echo '
        <form action="' . VIRTUAL_PATH . '/RSS/feed_edit.php" method="post" onsubmit="return checkForm(this);">
        <table align="center">
            <tr>
                <td align="right" style="padding-right: 1em;">Select Category:  </td>
                <td>
                ';
                
                $sql = "SELECT * FROM p4_feeds";
                $result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));
                while($row = mysqli_fetch_assoc($result)){
        
                    echo '
                    <input type="radio" name="FeedID" value="' . (int)$row['FeedID'] . '" />' . dbOut($row['Title']) . '<br />
                    ';
                }
                
        echo '
                </td>
            </tr>
	   		<td align="center" colspan="2">
	   			<input type="submit" value="Edit Feed" />
                <input type="hidden" name="cat" value="feedback" />
	   		</td>
	   	</tr>
        </table>    
        </form>
        ';
}
    
get_footer();
?>
