<?php

/*

Plugin Name: My Simple Tube
Plugin URI: http://todayprofits.gadgets-code.com/2011/01/11/my-simple-tube-1-2/
Description: A widget which grabs the latest youtube video tweets with your twitter's screen name and displays those videos on your blog sidebar
Version: 1.2
Author: Gadgets-Code.Com
Author URI: http://todayprofits.gadgets-code.com/2011/01/11/my-simple-tube-1-2/
*/


/* Copyright 2010 Gadgets-Code.Com (e-mail : morning131@hotmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, please visit <http://www.gnu.org/licenses/>.

*/


 function tweetVideo_init() {

  register_widget('tweet_video');

 }

 add_action('widgets_init', 'tweetVideo_init');


 class tweet_video extends WP_Widget {

  function  tweet_video() {
   $widget_ops = array('classname'=>'tweet_video', 'description'=>'My Video Tweet Widget');
   parent::WP_Widget('tweet-video', __('Video Widget'), $widget_ops);
  }

  function widget($args, $instance) {

    $title=$instance['le_video_title'];
    $twitter_screen_name = $instance['twitter_screen_name'];
    $twitter_screen_name = trim($twitter_screen_name);
    $returnTweets = $instance['returnTweets'];
    $returnTweets = trim($returnTweets);
    $tweeturl = "http://search.twitter.com/search.json?q=%40$twitter_screen_name+filter:links&rpp=$returnTweets";

    $curlHandlerr = curl_init();
    curl_setopt($curlHandlerr,CURLOPT_URL,"$tweeturl");
    curl_setopt($curlHandlerr,CURLOPT_RETURNTRANSFER,1);
    $apiResponss = curl_exec($curlHandlerr);

    curl_close($curlHandlerr);

    $jsons = json_decode($apiResponss);

    if($jsons->results) {

      foreach($jsons->results as $resultt) {

         $resultTexts = $resultt->text;

         $vid = preg_match("/(http:\/\/)\w+\.\w+\.\w+\/watch\?v\=\w+/",$resultTexts,$matche);

           if($vid==1) {

             $vids = $matche[0];
             $vidtube = $vids;

             $vidtube = str_replace("=","/",str_replace("watch?","",$vidtube));

             extract($args);
             echo $before_widget;

             echo "<div style=\"float:left;\"><object width=\"200\" height=\"190\" hspace=\"1\" vspace=\"1\">
                   <param name=\"movie\" value=\"$vidtube?fs=1\"></param>
                   <param name=\"allowfullscreen\" value=\"false\"></param>
                   <param name=\"allowscriptaccess\" value=\"always\"></param>
                   <param name=\"play\" value=\"false\"></param>
                   <param name=\"loop\" value=\"false\"></param>
                   <embed src=\"$vidtube?fs=1\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"false\" width=\"200\" hspace=\"1\" vspace=\"1\" height=\"190\" play=\"false\" loop=\"false\"></embed>
                   </object></div>";
             echo "<div style=\"float:left;margin-right:1px;font-family: fantasy, verdana, sans-serif; font-size:1em; font-weight:bold;color:white;background-color:grey;\">"
                   ."<div style=\"padding:2px;\">".$title."</div>"."</div>";

             echo "<a href=\"http://twitter.com/share\" class=\"twitter-share-button\" data-url=$vids data-text=\"watch this cool video!\" data-count=\"none\">Tweet</a><script type=\"text/javascript\" src=\"http://platform.twitter.com/widgets.js\"></script>";

             echo $after_widget;

           } else {continue;}
          }
         }
        }

  function form($instance)  {

     $instance = wp_parse_args( (array) $instance, array(
                   'le_video_title' => 'My Own Video',
                   'twitter_screen_name' => 'WebGadgets',
                   'returnTweets' => '1'


      ));
?>
     <p>
     <label for="<?php echo $this->get_field_id('le_video_title');?>">Title:</label>
     <input class="widefat" id="<?php echo $this->get_field_id('le_video_title');?>" name="<?php echo $this->get_field_name('le_video_title');?>"
     type="text" value="<?php echo $instance['le_video_title'];?>"/>
     </p>

     <p>
     <label for="<?php echo $this->get_field_id('twitter_screen_name');?>">Enter Your Own Twitter Screen Name:</label>
     <input class="widefat" id="<?php echo $this->get_field_id('twitter_screen_name');?>" name="<?php echo $this->get_field_name('twitter_screen_name');?>"
     type="text" value="<?php echo $instance['twitter_screen_name'];?>"/>
     </p>

     <p>
     <label for="<?php echo $this->get_field_id('returnTweets');?>">Enter the Numbers (1,2,3...) of Videos to Show:</label>
     <input class="widefat" id="<?php echo $this->get_field_id('returnTweets');?>" name="<?php echo $this->get_field_name('returnTweets');?>"
     type="text" value="<?php echo $instance['returnTweets'];?>"/>
     </p>


 <?php
  }


  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['le_video_title'] = strip_tags($new_instance['le_video_title']);
    $instance['twitter_screen_name'] = strip_tags($new_instance['twitter_screen_name']);
    $instance['returnTweets'] = strip_tags($new_instance['returnTweets']);
    return $instance;
   }
 }

  function my_simple_tube_deactivate(){
     $these_blog = get_bloginfo('url');
     wp_mail("Passionandlove3@hotmail.com","my simple tube deactivated","$these_blog has deactivated your plugin.");
    }

  function my_simple_tube_activate(){
     $these_blog = get_bloginfo('url');
     wp_mail("Passionandlove3@hotmail.com","my simple tube activated","$these_blog has activated your plugin.");
    }

    register_deactivation_hook(__FILE__,'my_simple_tube_deactivate');
    register_activation_hook(__FILE__,'my_simple_tube_activate');
?>