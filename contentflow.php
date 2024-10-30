<?php
/*
 Plugin Name: Content Flow3D
 Plugin URI: https://www.e-junkie.com/ecom/gb.php?cl=136641&c=ib&aff=170811
 Description: A simple 3D Image Rotator
 Version: 1.1
 Author: Zdrobau Valeriu

 Copyright 2012 Zdrobau Valeriu (email : valeriuzdrobau@gmail.com)

*/

    $baseContentFlowDir = WP_PLUGIN_DIR . '/' . str_replace(basename( __FILE__), "" ,plugin_basename(__FILE__));
    $baseContentFlowURL = WP_PLUGIN_URL . '/' . str_replace(basename( __FILE__), "" ,plugin_basename(__FILE__));
    define( 'CONTENT_FLOW_DIR',  $baseContentFlowDir);
    define( 'CONTENT_FLOW_URL',  $baseContentFlowURL);


    add_action('wp_print_scripts','content_flow_load_scripts');
    add_action('wp_print_styles','content_flow_load_styles');

    function content_flow_load_scripts ()
    {
        wp_register_script('content_flow_script',CONTENT_FLOW_URL . 'js/contentflow_src.js', false, '1.0.0');
        wp_enqueue_script('content_flow_script');
    }

    function content_flow_load_styles ()
    {
        wp_register_style('content_flow_style', CONTENT_FLOW_URL . 'css/contentflow.css', false, '1.0.0');
        wp_enqueue_style('content_flow_style');
    }

/******************* START Shortcode  *********************/

 function content_flow_add_button() {

   // Don't bother doing this stuff if the current user lacks permissions
   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
     return;

   // Add only in Rich Editor mode
  // if ( get_user_option('rich_editing') == 'true') {
     add_filter("mce_external_plugins", "content_flow_add_tinymce_plugin");
     add_filter('mce_buttons', 'content_flow_register_button');
   //}
}

function content_flow_register_button($buttons) {

        array_push($buttons, "separator", "content_flow");
        return $buttons;
}


function content_flow_add_tinymce_plugin($plugin_array) {

        $plugin_array['content_flow'] = CONTENT_FLOW_URL . 'shortcode/js/extend_tiny_mce.js';
        return $plugin_array;
}

// init process for button control
add_action('init', 'content_flow_add_button');

/******************* END Shortcode *********************/



function content_flow_shortcode($atts){

    extract(shortcode_atts(array(
                                  'items' => 0,
                                  'align' => 'center',
                                  'width' => 600,
                                  'height' => 300,
                                  'bg' => 'transparent',
                                  'image_number' => 0,
                                  //slider
                                  'slider' => false,
                                  'sl_cursor' => 'move',
                                  'sl_bg' => '#0000ff',
                                  'scrollcolor' => 'white',
                                  //Caption
                                  'caption' => true,
                                  'caption_bg' => '#FF0000',
                                  //Generic
                                  'load' => 30000,
                                  'circular' => true,
                                  'items' => 3, //Number of items to show on either side of the active Item. If set to '0' it will be set to the square root of the number of items in the flow.
                                  'verticalFlow' => false, //Will turn the ContentFlow 90 degree counterclockwise.This will automatically swap calculated positions and sizes where needed. You do not have to adjust any calculations or sizes.
                                  'end_opacity' => 0.3, //flaot,The opacity of the last visible item on either side. The opacity of each item will be calculated by the 'calcOpacity' function.
                                  //Items
                                  'reflectionHeight' => 0.3,// float,Set the size of the reflection image relative to the original image
                                  'rg' => 0,// float,Set the size of the gap between the image and the reflection image relative to the original image size
                                  'reflclr' => "transparent",// string ,Set the "surface"-color of the reflection. Can be "none", "transparent", or #RRGGBB (hex RGB values)If set to 'overlay' the image given by the option 'reflectionOverlaySrc' will be lain over the reflection.
                                  'scalefactor' => 1.0,// float,Factor by which the item will be scaled.
                                  'scalefl' => 1.0,// float | string (default: 1.0)float := Factor to scale content images in landscape format by.string := if set to 'max' the height of an landscape image content will be set to the height of the item.
                                  'scalefp' => 1.0,//  float | string (default: 1.0)float := Factor to scale content images in portrait format by.string := if set to 'max' the width of an portait image content will be set to the width of the item.
                                  'fixsize' => false,//  bool Fixes the item size, to the calculated size. No adjustments will be done. Images will be croped if bigger.
                                  'maxheight' => 0,//  int Maximum item height in px. If set to a value greater than '0' the item size will be calculated from this value instead relative to the width of the ContentFlow.
                                  'relativeip' => "top/center",//  string Position of item relative to it's coordinate.It can contain the keywords top/above, bottom/below, left, right and center.So by default the item will be placed above the coordinate point and centered horizontally.If set this option overrides the calcRelativeItemPosition option!
                                  //Flow Interaction
                                  'speedf' => 0.3, //float A flowSpeedFactor > 1 will speedup the scrollspeed, while a factor between 0 and 1 will slow it down
                                  'flowDragFriction' => 1.0, //float Determines how hard it is to drag the flow.If set to '0' dragging of the flow is deactivated.
                                  'scrollws' => 1.0, //float Scales by how many items the flow will be moved with one usage of the mousewheel.Negative values will reverse the scroll direction.If set to '0' scrolling with the mouse wheel is deactivated.
                                  'keys' => true
                           ), $atts));

        $contentId = rand();
        $content = array();

        $items = intval($items);
        $i = 1;
        while($i<=$items)
        {
            $content['src'][$i] = '';
            $content['caption'][$i] = '';
            $content['slcaption'][$i] = '';
            $content['title'][$i] = '';
            $content['link'][$i] = '#';
            $content['target'][$i] = '_self';
            $i++;

        }
        foreach ($atts as $key => $value)
        {
            $pos = strpos($key,'src_');
            if( $pos !== false )
            {
                $key = str_replace('src_','',$key);
                $i = intval($key);
                $content['src'][$i] = $value;

            }

            $pos = strpos($key,'tcaption_');
            if ( $pos !== false )
            {
                $key = str_replace('tcaption_','',$key);
                $i = intval($key);
                $content['caption'][$i] = $value;
            }

            $pos = strpos($key,'slcaption_');
            if ( $pos !== false )
            {
                $key = str_replace('slcaption_','',$key);
                $i = intval($key);
                $content['slcaption'][$i] = $value;
            }

            $pos = strpos($key,'title_');
            if ( $pos !== false )
            {
                $key = str_replace('title_','',$key);
                $i = intval($key);
                $content['title'][$i] = $value;
            }

            $pos = strpos($key,'link_');
            if ( $pos !== false )
            {
                $key = str_replace('link_','',$key);
                $i = intval($key);
                $pos = strpos($value,'http://');
                if (!$pos) $value = 'http://' . $value;
                $content['link'][$i] = $value;
            }

            $pos = strpos($key,'target_');
            if ( $pos !== false )
            {
                $key = str_replace('target_','',$key);
                $i = intval($key);
                $content['target'][$i] = $value;
            }
        }
        $html = "";
        $html .= "<script>";
             $html .= "var myNewFlow" . $contentId . " = new ContentFlow('ContentFlow" . $contentId . "', {";
                //Generic
                $html .= "loadingTimeout: " . $load . ",";
                if ( $circular == '0' ) $html .= "circularFlow: false,";
                $html .= "visibleItems: " . $items . ",";
                if ( $verticalFlow ) $html .= "verticalFlow: " . $verticalFlow . ",";
                $html .= "endOpacity: " . $end_opacity . ",";

                //Items
                $html .= "reflectionHeight: " . $reflectionHeight . ",";
                $html .= "reflectionGap: " . $rg . ",";
                $html .= "reflectionColor :'" . $reflclr ."',";
                $html .= "scaleFactor : " . floatval($scalefactor) . ",";
                $html .= "scaleFactorLandscape: " . floatval($scalefl) .",";
                $html .= "scaleFactorPortrait: " . floatval($scalefp) .",";
                if ( $fixsize == 1 ) $html .= "fixItemSize: true,";
                $html .= "maxItemHeight: " . $maxheight . ",";
                $html .= "relativeItemPosition: '" . $relativeip . "',";
                //Flow Interaction
                $html .= "reflectionColor : 'transparent',";
                $html .= "flowSpeedFactor: " . $speedf . ",";
                $html .= "flowDragFriction: " . $flowDragFriction . ",";
                $html .= "scrollWheelSpeed: " . $scrollws . "," ;
                if ($keys == 0) $html .= " keys: {}";
                $html .="} ) ;";
        $html .= "</script>";
        //STYLE
        $html .= '<style type="text/css">';
            $html .= '#ContentFlow' . $contentId;
            $html .= '{';
            $html .= 'margin:7px;';
            if ( $align == 'left' || $align == 'right' ) $html .= 'float: ' . $align . ';' ;
            $html .= 'background-color: ' . $bg . ';' ;
            $html .= '}';
        $html .='</style>';
        //HTML
        $html .= '<div id="ContentFlow' . $contentId . '" style="width:' . $width . 'px; height: ' . $height . 'px;">';
            $html .= '<div class="loadIndicator"><div class="indicator"></div></div>';
            $html .= '<div class="flow">';

                for ($i=1;$i<=$items;$i++)
                {
                    if (!$content['src'][$i]) continue;
                    $html .= '<div class="item">';
                        $html .= '<img class="content" src="' . CONTENT_FLOW_URL . 'shortcode/uploads/' . $content['src'][$i] . '" ';
                        if ( $content['link'][$i]!='#') $html .= 'href="' . $content['link'][$i] . '" target="' . $content['target'][$i] . '" '; else $html .= 'href="javascript:void(0)"';
                        $html .= 'title="' . $content['title'][$i] . '" />';
                    if ( $caption !=='0' ) $html .= '<div class="caption">' . $content['caption'][$i] . '</div>';
                    if ( $slider) $html .= '<div class="label">' . $content['slcaption'][$i] . '</div>';
                    $html .= '</div>';
                }
                 for ($i=1;$i<=$items;$i++)
                {
                    if (!$content['src'][$i]) continue;
                    $html .= '<div class="item">';
                        $html .= '<img class="content" src="' . CONTENT_FLOW_URL . 'shortcode/uploads/' . $content['src'][$i] . '" ';
                        if ( $content['link'][$i]!='#') $html .= 'href="' . $content['link'][$i] . '" target="' . $content['target'][$i] . '" '; else $html .= 'href="javascript:void(0)"';
                        $html .= 'title="' . $content['title'][$i] . '" />';
                    if ( $caption !=='0' ) $html .= '<div class="caption">' . $content['caption'][$i] . '</div>';
                    if ( $slider) $html .= '<div class="label">' . $content['slcaption'][$i] . '</div>';
                    $html .= '</div>';
                }

            $html .= '</div>';//end flow div
            if ( $caption !=='0' ) $html .= '<div class="globalCaption" style="color: ' . $caption_bg . ';"></div>';
            if ( $slider) $html .= '<div class="scrollbar" style=" background: url( \''. CONTENT_FLOW_URL . 'css/img/scrollbar_' . $scrollcolor . '.png\') left center repeat-x;"><div class="slider" style="color: red; background: url( \''. CONTENT_FLOW_URL . 'css/img/slider_' . $scrollcolor . '.png\'); cursor: ' . $sl_cursor . ';"><div class="position" style="color:' . $sl_bg . '"></div></div></div>';
        $html .= '</div>';

    return $html;
}

add_shortcode('content_flow', 'content_flow_shortcode');

?>