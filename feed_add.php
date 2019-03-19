<?php

/**
 * feed_add.php allows users to add the database new RSS feeds to use with feed.php
 *
 * @author Michael Rodgers <michael.rodgers312@gmail.com>
 * @version 2019-03-19
 * @link http://michaelrodgers.azurewebsites.net/wn19/RSS/feed_add.php
 * @
 * @see feed.php 
 * @see config_inc.php
 * @todo none
 */

require '../inc_0700/config_inc.php';
get_header();
    
    echo '
    <a href="feed_add.php" >Add To Feed</a><br />
    <a href="feed_edit.php" >Edit Feed</a><br />';
    
    if (isset($_POST['add'])){
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
        
    } else { //show form - provide feedback
        $config->loadhead= '
        <script type="text/javascript" src="' . VIRTUAL_PATH . 'include/util.js"></script>
        <script type="text/javascript">
                function checkForm(thisForm)
                {//check form data for valid info
                    if(empty(thisForm.FeedURL,"Please enter the URL associated with this RSS feed")){return false;}
                    return true;//if all is passed, submit!
                }
        </script>
        ';

        echo '
        <h3 align="center">Add New RSS Feed</h3>
        <form action="' . VIRTUAL_PATH . '/RSS/feed_add.php" method="post" onsubmit="return checkForm(this);">
        <table align="center">
            <tr>
                <td align="right" style="padding-right: 1em;">Select Category:  </td>
                <td>
                ';
                
                $sql = "SELECT * FROM p4_feeds";
                $result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));
                while($row = mysqli_fetch_assoc($result)){
        
                    echo '
                    <input type="checkbox" name="FeedID" value="' . (int)$row['FeedID'] . '" />' . dbOut($row['Title']) . '<br />
                    ';
                }
                
        echo '
                </td>
            </tr>
            <tr>
                <td align="right" style="padding-right: 1em;">Enter Feed URL:   </td>
                <td>
                    <input type="url" name="FeedURL" />
                </td>
            </tr>
            <tr>
	   		<td align="center" colspan="2">
	   			<input type="submit" value="Add Feed" />
                <input type="hidden" name="add" value="feedback" />
	   		</td>
	   	</tr>
        </table>    
        </form>
        ';
    }
    
 get_footer();
?>
