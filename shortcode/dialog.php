
<head>
	<title>Content Flow</title>
    <link href="css/fileuploader.css" rel="stylesheet" type="text/css">
    <link href="css/colorpicker.css" rel="stylesheet" type="text/css">
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="../../../../wp-includes/js/jquery/jquery.js"></script>
    <script type="text/javascript" src="js/tiny_mce_popup.js"></script>
    <script type="text/javascript" src="js/mctabs.js"></script>
    <script type="text/javascript">
            var baseUrl = window.location.href;
            var pos = baseUrl.indexOf('/',7);
            baseUrl = baseUrl.substring(0, pos);
            try
            {
                var secretcode = tinyMCEPopup.getWindowArg ('security_code','null');
            }
            catch (ex)
            {
                window.location.href = baseUrl;
            }
            secretcode = parseFloat(secretcode)
             if (typeof (secretcode) != 'number' )  window.location.href = baseUrl;
             if (secretcode < 0 || secretcode>1)   window.location.href = baseUrl;

    </script>

    <script type="text/javascript" src="js/fileuploader.js"></script>
    <script type="text/javascript" src="js/tooltip.js"></script>
    <script type="text/javascript" src="js/colorpicker.js"></script>
    <script type="text/javascript" src="js/eye.js"></script>
    <script type="text/javascript" src="js/layout.js"></script>

    <style>

        .content_flow_color_select {
            border-radius: 6px;
            -moz-border-radius: 6px;
        }
        .options_outline:focus{
            border: 1px solid #11a6eb;
        }
        .options_outline{
            -moz-border-radius: 3px;
            -webkit-border-radius: 3px;
            -khtml-border-radius: 3px;
            border-radius: 3px;
        }

        .text_settings{
            margin-top: 5px;
            width: 250px;
            height: 20px;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
        }
        .text_settings:focus{
            border: 1px solid #11a6eb;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
        }
        .friends{
            width: 490px;
            height: 140px;
            border: none;
            align:center;
            vertical-align:middle;
            display:inline;
        }

        .friends a img {
            border: 5px solid #333333;
            margin-left: 23px;
        }
        .content .preview{
            position: absolute;
            top:110px;
            left:45px;
            width: 110px;
            height: 110px;
            border: 1px solid red;
        }
        .content .upload_button{
            position: absolute;
            top:224px;
            left:45px;
        }
        .content .settings{
            position: absolute;
            top:110px;
            left:260px;
        }

        .content .settings_label{
            position: absolute;
            top:121px;
            left:168px;
        }
        .im_target{
            margin-top: 7px;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
        }
        .im_target:focus{
             border: 1px solid #11a6eb;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
        }


	</style>
	<script type="text/javascript">

        jQuery.noConflict();
        (function($) {

        jQuery(document).ready(function() {

            var address = window.location.href;
            var pos = address.indexOf('dialog.php');
            address = address.substring(0,pos);

            var elegantthemes = jQuery('.elegantthemes').attr('src',address+'/images/friends/e_125x125.gif')
            var themefuse = jQuery('.themefuse').attr('src',address+'/images/friends/t_125x125.png')
            var wpzoom = jQuery('.wpzoom').attr('src',address+'/images/friends/z_125x125.png')

           var address = window.location.href;
           var myRegExp = /shortcode/;
           var number = address.search(myRegExp);
           root_address = address.slice(0,number);
           address = address.slice(0,number+10);
           action_address = address + 'upload.php';
           upload_address = address + 'uploads/'

           $('#input_tabs_number, #width, #height, #loading, #visible_items, #maxItemHeight').live('keypress', function(e)
           {
                if ((( e.which >47) && (e.which <58)) || e.which == 8) return true;
                return false;
           });

             $('#end_opacity, #reflection_height, #reflection_gap, #scaleFactorPortrait, #scaleFactorLandscape, #SpeedFactor, #drag_friction').live('keypress', function(e)
           {
                if ((( e.which >47) && (e.which <58)) || e.which == 8 || e.which == 46) return true;
                return false;
           });

            $('#end_opacity').live('blur', function(e)
           {
                //if ((( e.which >47) && (e.which <58)) || e.which == 8 || e.which == 46) return true;
                return false;
           });

           $('#tabs_number').live('click', function(){

                   var number_tabs = parseInt($("#input_tabs_number").val());
                   if ($("#input_tabs_number").val() == '') return false;//in caz ka nu am completat cite imagini avem nevoie
                   if ( ( number_tabs > 0 ) && ( number_tabs < 99 ) )
                   {
                       jQuery('#insert').removeAttr('disabled');
                       jQuery('#apply').removeAttr('disabled');

                   }
                    else
                       return false;

                var html = '<fieldset style="height: 240px;"><legend>Slider Content</legend><br /><select name="content_flow_select_number_tabs" id="content_flow_select_number_tabs" style="width: 100px">';


                    for (var i=1;i<=number_tabs;i++)
                    {
                        html += '<option value="' + i + '"> Item ' + i +' </option>';
                    }
                    html += '</select><br /><br />';
                    html +='<input id="items" type="hidden" value="' + number_tabs+ '">';
                    html += '<fieldset style="height: 178px;">';

                    var displayStyle = 'block';
                    for (var i=1;i<=number_tabs;i++)
                    {
                        if ( i != 1 ) {displayStyle = 'none'} else {displayStyle='block'}
                        html += '<div class="content changable_' + i + ' simple_accordion_div_for_hide" style="display: '+ displayStyle + '">';
                        html += '<div class="preview">';
                        html += '<img class="img_preview" id="preview_' + i + '" src="' + root_address + 'includes/timthumb/timthumb.php?src=' + address + 'images/noimage.png&w=110&h=110">';
                        html += '</div>';
                        html +='<div class="settings_label">';
                        html += '<div class="hotspot" onmouseover="tooltip.show(\'Item caption\');" onmouseout="tooltip.hide();">Caption</div>';
                        html += '<br /><br /><div class="hotspot" onmouseover="tooltip.show(\'Slider Caption\');" onmouseout="tooltip.hide();">SL Caption</div>';
                        html += '<br /><br /><div class="hotspot" onmouseover="tooltip.show(\'Title of the image\');" onmouseout="tooltip.hide();">Title</div>';
                        html += '<br /><br /><div class="hotspot" onmouseover="tooltip.show(\'Image Link\');" onmouseout="tooltip.hide();">Link</div>';
                        html += '<br /><br /><div class="hotspot" onmouseover="tooltip.show(\'Image Link\');" onmouseout="tooltip.hide();">Target</div>';
                        html +='</div>';
                        html +='<div class="settings">'
                        html += '<input type="text" id="caption_' + i + '" class="text_settings">';
                        html += '<input type="text" id="sl_caption_' + i + '" class="text_settings">';
                        html += '<input type="text" id="title_' + i + '" class="text_settings">';
                        html += '<input type="text" id="im_link_' + i + '" class="text_settings" value="#" onfocus="if (this.value == \'#\') {this.value = \'\';}" onblur="if (this.value == \'\') {this.value = \'#\';}">';
                        html += '<select class="im_target" id="target_'+i+'">';
                        html += '<option value="_self" selected>Self</option>';
                        html += '<option value="_blank">Blank</option>';
                        html += '</select>';
                        html += '<input type="hidden" id="src_hidden_' + i + '" value="">';
                        html +='</div>';

                        html +='<div class="upload_button">';
                        html +='<div id=\"file-uploader-demo' + i + '\"><noscript><p>Please enable JavaScript to use ContentFlow.</p></noscript></div>';
                        html +='</div>';
                        html +='</div>';
                    }
                    html +='</fieldset>';

                   var script_content = '';

                    for (var i=1;i<=number_tabs;i++)
                    {
                        script_content += 'var imagine' + i + ' =  new qq.FileUploader({';
                        script_content += 'element: document.getElementById(\'file-uploader-demo' + i + '\'),';
                        script_content += 'action: \'' + action_address + '\',';
                        script_content += 'debug: true,';
                        script_content += 'onSubmit: function(file, extension) {';
                        script_content += 'jQuery(\'#preview_'+i+'\').attr("src",root_address + \'shortcode/images/progress.gif\');';
                        script_content += '},';
                        script_content += 'onComplete: function(id, fileName, responseJSON) {'
                        script_content += 'var ext = fileName.substr(fileName.lastIndexOf(\'.\'));'
                        script_content += 'jQuery(\'#preview_'+i+'\').attr("src",root_address + \'includes/timthumb/timthumb.php?src=\' + upload_address + responseJSON["file_name"] + ext+ \'&w=110&h=110\');';
                        script_content +='var source = responseJSON["file_name"] + ext;';
                        script_content += 'jQuery(\'#src_hidden_'+i+'\').val(source);';
                        script_content += '}';
                        script_content += '}); ';
                    }

                $('#tabs_number_fieldset').fadeOut(1000).remove();
                $('#simple_accordion_hidden').hide().html(html).fadeIn(1000);

                var script   = document.createElement("script");
                    script.type  = "text/javascript";
                    script.text  = script_content;               // use this for inline script
                    document.body.appendChild(script);


                });

		    });

    function colorToHex(rgb){
     rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
     return "#" +
      ("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
      ("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
      ("0" + parseInt(rgb[3],10).toString(16)).slice(-2);
    }

    $('#content_flow_select_number_tabs').live('change', function(){
        var i = $(this).val();
        $('.simple_accordion_div_for_hide').fadeOut(1000);


        function second_passed() {
        $('.changable_' + i).fadeIn(1000);
        }
        setTimeout(second_passed, 980)

    });

    $('#insert').live('click', function (){

    var x = ' [content_flow';

    var align =  jQuery('#align').val();
    if (jQuery('#keys').is(':checked')){var keys = 1;} else {var keys = 0;};
    var width =  jQuery('#width').val();
    if (jQuery('#transparent').is(':checked')){var transparent = 1;} else {var transparent = 0;};
    var background =  jQuery('#background').css("background-color");
        background = colorToHex(background);
    var height =  jQuery('#height').val();
    if (jQuery('#caption').is(':checked')){var caption = 1;} else {var caption = 0;};
    var caption_bg =  jQuery('#caption_background').css("background-color");
    caption_bg = colorToHex(caption_bg);
    if (jQuery('#slider').is(':checked')){var slider = 1;} else {var slider = 0;};
    var sl_cursor =  jQuery('#slider_cursor').val();
    var sl_bg =  jQuery('#sl_color').css("background-color");
    sl_bg = colorToHex(sl_bg);
    //scroolbar color
    var scroll_color = jQuery('#scroll_color').val();

    var loading =  jQuery('#loading').val();
    if (jQuery('#circular').is(':checked')){var circular = 1;} else {var circular = 0;};
    var visible_items =  jQuery('#visible_items').val();
    var end_opacity =  jQuery('#end_opacity').val();
    var reflectionHeight =  jQuery('#reflection_height').val();
    var reflectionGap =  jQuery('#reflection_gap').val();
    /*var reflectionColor =  jQuery('#reflection_color').val();
    reflectionColor = colorToHex(reflectionColor);*/
    var scaleFactor =  jQuery('#scalefactor').val();
    var scalefl =  jQuery('#scaleFactorLandscape').val();
    var scalefp =  jQuery('#scaleFactorPortrait').val();
    if (jQuery('#fixItemSize').is(':checked')){var fixsize = 1;} else {var fixsize = 0;};
    var maxheight =  jQuery('#maxItemHeight').val();
    var relativeip =  jQuery('#relativeItemPosition').val();
    var SpeedFactor =  jQuery('#SpeedFactor').val();
    var drag_friction =  jQuery('#drag_friction').val();
    var scrollWheelSpeed =  jQuery('#scrollWheelSpeed').val();
    var items = jQuery('#items').val();

    x += ' items=' + items;
    if (align != 'center') x += ' align=' + align;
    if (width != 600) x += ' width=' + width;
    if ( transparent == 0 ) x += ' bg=' + background;
    if (height != 300) x += ' height=' + height;
    //Caption
    if ( caption == 0)
    {
        x+=' caption=0';
    }else
    {
       if (caption_bg != '#ff0000'){ x+=' caption_bg=' + caption_bg;}
    }
    //Slider
    if ( slider == 1)
    {
        x+=' slider=1';
        if ( sl_bg !='#0000ff') {x+=' sl_bg=' + sl_bg;}
        if (sl_cursor != 'move') x += ' sl_cursor=' + sl_cursor;
        if (scroll_color != 'white') x += ' scrollcolor=' + scroll_color;
    }
    if (loading !=30000){ x += ' load=' + loading;}
    if (circular == 0){ x += ' circular=0';}
    if (visible_items !=3){ x += ' items='+ visible_items;}
    if (end_opacity !=0.3){ x += ' end_opacity='+ end_opacity;}
    if (reflectionHeight !=0.3){ x += ' reflectionHeight='+ reflectionHeight;}
    if (reflectionGap !=0.0){ x += ' rg='+ reflectionGap;}
    /*if (reflectionColor != '#ff0000') { x+= ' reflclr='+reflectionColor;}*/
    if (scaleFactor !=1.0){ x += ' scaleFactor='+ scaleFactor;}
    if (scalefl !=1.0){ x += ' scalefl='+ scalefl;}
    if (scalefp !=1.0){ x += ' scalefp='+ scalefp;}
    if (fixsize == 1){ x += ' fixsize='+ fixsize;}
    if (maxheight != 0 ){ x += ' maxheight='+ maxheight;}
    if (relativeip != 'top/center' ){ x += ' relativeip='+ relativeip;}
    if (keys == 0 ){ x += ' keys=0';}
    if (SpeedFactor != 0.3 ){ x += ' speedf=' + SpeedFactor;}
    if (scrollWheelSpeed != 1.0 ){ x += ' scrollws=' + scrollWheelSpeed;}
    //Items

    for (var j=1; j<=items;j++)
    {
        var src = jQuery('#src_hidden_' + j).val();
        var caption = jQuery('#caption_'+j).val();
        var sl_caption = jQuery('#sl_caption_'+j).val();
        var title = jQuery('#title_'+j).val();
        var link = jQuery('#im_link_'+j).val();
        var target = jQuery('#target_'+j).val();

        if (src)
        {
            x +=' src_' + j + '=' + src;
            if (caption) {x +=' tcaption_' + j + '=' + caption;}
            if (sl_caption) x +=' slcaption_' + j + '=' + sl_caption;
            if (title)  x +=' title_' + j + '=' + title;
            if (link && (link != '#'))  x +=' link_' + j + '=' + link;
            if (target != '_self')  x +=' target_' + j + '=' + target;
        }

    }

    x += '] ';

    Simple_Accordion.insert(x);
    tinyMCEPopup.close();
});
 })(jQuery);

var Simple_Accordion = {
	init : function(ed) {
		tinyMCEPopup.resizeToInnerSize();
	},

	insert : function insertEmotion(code) {

    	tinyMCEPopup.execCommand('mceInsertContent', false, code);

	}
};
tinyMCEPopup.onInit.add(Simple_Accordion.init, Simple_Accordion);

document.write('<base href="'+tinymce.baseURL+'" />');


</script>
</head>
<body>
<form action="#" id="simple_accordion_form">
		<div class="tabs">
			<ul>
				<li id="general_tab" class="current" aria-controls="general_panel"><span><a href="javascript:mcTabs.displayTab('general_tab','general_panel');" onmousedown="return false;">General</a></span></li>
				<li id="flow_tab" aria-controls="flow_panel"><span><a href="javascript:mcTabs.displayTab('flow_tab','flow_panel');" onmousedown="return false;">Items</a></span></li>
				<li id="content_tab" aria-controls="content_tab"><span><a href="javascript:mcTabs.displayTab('content_tab','content_panel');" onmousedown="return false;">Content</a></span></li>
			</ul>
        <div class="panel_wrapper" style="height: 410px">
			<div id="general_panel" class="panel current">
                <fieldset>
                    <legend>General Settings</legend>
                        <table role="presentation" class="properties">
                            <tr>
                                <td width="100px">
                                    <div class="hotspot" onmouseover="tooltip.show('Width of the slider in pixels');" onmouseout="tooltip.hide();">Width</div>
                                </td>
                                <td width="60px">
                                    <input id="width" class="options_outline" type="text" style="width: 40px;" value="600" maxlength="4">
                                </td>
                                <td width="100px">
                                    <div class="hotspot" onmouseover="tooltip.show('Height of the slider in pixels');" onmouseout="tooltip.hide();">Height</div>
                                </td>
                                <td width="60px">
                                    <input id="height" class="options_outline" type="text"  style="width: 40px;" value="300" maxlength="4">
                                </td>
                            </tr>
                            <tr>
                                <td width="100px">&nbsp;</td>
                                <td width="60px">&nbsp;</td>
                                <td width="100px">&nbsp;</td>
                                <td width="60px">&nbsp;</td>
                            </tr>
                              <tr>
                                <td width="100px">
                                    <div class="hotspot" onmouseover="tooltip.show('Set background transparency');" onmouseout="tooltip.hide();">Transparent</div>
                                </td>
                                <td width="60px">
                                    <input id="transparent" class="options_outline" type="checkbox" checked="checked">
                                </td>
                                <td width="100px">
                                    <div class="hotspot" onmouseover="tooltip.show('Set the background color only if the transparency is deactivated');" onmouseout="tooltip.hide();">Background Color</div>
                                </td>
                                <td width="60px">
                                    <input type="text" id="background" class="content_flow_color_select" style="background-color: #ffffff">
                                </td>
                            </tr>
                            <tr>
                                <td width="100px">&nbsp;</td>
                                <td width="60px">&nbsp;</td>
                                <td width="100px">&nbsp;</td>
                                <td width="60px">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="100px">
                                    <div class="hotspot" onmouseover="tooltip.show('Slider alignment in content');" onmouseout="tooltip.hide();">Align</div>
                                </td>
                                <td width="60px">
                                    <select id="align" class="options_outline">
                                        <option value="center" selected="">Center</option>
                                        <option value="left">Left</option>
                                        <option value="right">Right</option>
                                    </select>
                                </td>
                                <td width="50px">
                                   <div class="hotspot" onmouseover="tooltip.show('Enable Key Navigation');" onmouseout="tooltip.hide();">Key Navigation</div>
                                </td>
                                <td width="110px">
                                   <input type="checkbox" id="keys" checked="checked">
                                </td>
                            </tr>
                        </table>
                </fieldset>
                <fieldset>
                    <legend>General Settings</legend>
                        <table role="presentation" class="properties">
                            <tr>
                                <td width="100px">
                                    <div class="hotspot" onmouseover="tooltip.show('Enable/Disable image caption');" onmouseout="tooltip.hide();">Enable Caption</div>
                                </td>
                                <td width="60px">
                                    <input id="caption" class="options_outline" type="checkbox" checked="checked">
                                </td>
                                <td width="100px">
                                    <div class="hotspot" onmouseover="tooltip.show('Set the title color if it is enabled');" onmouseout="tooltip.hide();">Caption Color</div>
                                </td>
                                <td width="60px">
                                   <input type="text" id="caption_background" class="content_flow_color_select" style="background-color: #ff0000">
                                </td>
                            </tr>
                            <tr>
                                <td width="100px">&nbsp;</td>
                                <td width="60px">&nbsp;</td>
                                <td width="100px">&nbsp;</td>
                                <td width="60px">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="100px">
                                    <div class="hotspot" onmouseover="tooltip.show('Activate the slider');" onmouseout="tooltip.hide();">Enable Slider</div>
                                </td>
                                <td width="60px">
                                    <input id="slider" class="options_outline" type="checkbox" >
                                </td>
                                <td width="100px">
                                    <div class="hotspot" onmouseover="tooltip.show('Slider cursor type');" onmouseout="tooltip.hide();">Slider Cursor</div>
                                </td>
                                <td width="60px">
                                    <select id="slider_cursor" class="options_outline">
                                       <option value="pointer" selected="selected">Pointer</option>
                                       <option value="move" selected="">Move</option>
                                   </select>
                                </td>
                            </tr>
                               <tr>
                                <td width="100px">&nbsp;</td>
                                <td width="60px">&nbsp;</td>
                                <td width="100px">&nbsp;</td>
                                <td width="60px">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="100px">
                                    <div class="hotspot" onmouseover="tooltip.show('Slider color description if it is enabled');" onmouseout="tooltip.hide();">Slider Color</div>
                                </td>
                                <td width="60px">
                                    <input type="text" id="sl_color" class="content_flow_color_select" style="background-color: #0000ff">
                                </td>
                                <td width="100px">
                                    <div class="hotspot" onmouseover="tooltip.show('Scrollbar color if it is enabled');" onmouseout="tooltip.hide();">Scrollbar Color</div>
                                </td>
                                <td width="60px">
                                    <select id="scroll_color">
                                        <?php
                                        $sliderColor = array('white'=>'White','red'=>'Red','cyan'=>'Cyan','blue'=>'Blue','dark_blue'=>'Dark Blue','light_purple'=>'Light Purple','purple'=>'Purple','yellow'=>'Yellow','liem'=>'Lime','fuchsia'=>'Fuchsia','silver'=>'Silver','grey'=>'Grey','black'=>'Black','orange'=>'Orange','brown'=>'Brown','maroon'=>'Maroon','green'=>'Green','olive'=>'Olive');
                                        foreach($sliderColor as $key => $value)
                                        {
                                            echo '<option value="' . $key . '">' . $value . '</option>';
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                </fieldset>
                <fieldset>
                    <legend>Generic Settings</legend>
                        <table role="presentation" class="properties">
                            <tr>
                                <td width="100px">
                                    <div class="hotspot" onmouseover="tooltip.show('Image loading time');" onmouseout="tooltip.hide();">Loading Time</div>
                                </td>
                                <td width="60px">
                                    <input id="loading" class="options_outline" type="text" style="width: 50px;" value="30000" maxlength="6">
                                </td>
                                <td width="100px">
                                   <div class="hotspot" onmouseover="tooltip.show('<strong>Bool</strong> Should the Flow wrap around?');" onmouseout="tooltip.hide();">CircularFlow</div>
                                </td>
                                <td width="60px">
                                    <input id="circular" class="options_outline" type="checkbox" checked="checked">
                                </td>
                            </tr>
                            <tr>
                                <td width="100px">&nbsp;</td>
                                <td width="60px">&nbsp;</td>
                                <td width="100px">&nbsp;</td>
                                <td width="60px">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="100px">
                                    <div class="hotspot" onmouseover="tooltip.show('Number of items to show on either side of the active Item. If set to \'0\' it will be set to the square root of the number of items in the flow.');" onmouseout="tooltip.hide();">Visible Items</div>
                                </td>
                                <td width="60px">
                                    <input id="visible_items" class="options_outline" type="text" style="width: 40px;" value="3" maxlength="1">
                                </td>
                                <td width="100px">
                                    <div class="hotspot" onmouseover="tooltip.show('<strong>Float</strong>,The opacity of the last visible item on either side. The opacity of each item will be calculated by the \'calcOpacity\' function.');" onmouseout="tooltip.hide();">EndOpacity</div>
                                </td>
                                <td width="60px">
                                    <input id="end_opacity" class="options_outline" type="text" style="width: 40px;" value="0.3" maxlength="4">
                                </td>
                            </tr>
                        </table>
                </fieldset>

			</div>
            <div id="flow_panel" class="panel">
                <fieldset>
                    <legend>Flow Settings</legend>
                        <table role="presentation" class="properties">
                            <tr>
                                <td width="100px">
                                    <div class="hotspot" onmouseover="tooltip.show('<strong>Float</strong>,Set the size of the reflection image relative to the original image');" onmouseout="tooltip.hide();">Reflection Height</div>
                                </td>
                                <td width="60px">
                                    <input id="reflection_height" class="options_outline" type="text" style="width: 40px;" value="0.3" maxlength="3">
                                </td>
                                <td width="100px">
                                    <div class="hotspot" onmouseover="tooltip.show('<strong>Float</strong>,Set the size of the gap between the image and the reflection image relative to the original image size');" onmouseout="tooltip.hide();">Reflection Gap</div>
                                </td>
                                <td width="60px">
                                    <input id="reflection_gap" class="options_outline" type="text" style="width: 40px;" value="0.0" maxlength="3">
                                </td>
                            </tr>
                            <tr>
                                <td width="100px">&nbsp;</td>
                                <td width="60px">&nbsp;</td>
                                <td width="100px">&nbsp;</td>
                                <td width="60px">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="100px">
                                    <div class="hotspot" onmouseover="tooltip.show('Set the Reflection Color');" onmouseout="tooltip.hide();">Reflection Color</div>
                                </td>
                                <td width="60px">
                                    <input type="text" id="reflection_color" class="content_flow_color_select" style="background-color: #ffffff">
                                </td>
                                <td width="100">
                                    <div class="hotspot" onmouseover="tooltip.show('<strong>Float</strong>,Factor by which the item will be scaled.');" onmouseout="tooltip.hide();">Scale Factor</div>
                                </td>
                                <td width="60px">
                                    <input id="scalefactor" class="options_outline" type="text" style="width: 40px;" value="1.0" maxlength="3">
                                </td>
                            </tr>
                            <tr>
                                <td width="100px">&nbsp;</td>
                                <td width="60px">&nbsp;</td>
                                <td width="100px">&nbsp;</td>
                                <td width="60px">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="100px">
                                    <div class="hotspot" onmouseover="tooltip.show('<strong>Float</strong> Factor to scale content images in landscape format by.');" onmouseout="tooltip.hide();">Factor Landscape</div>
                                </td>
                                <td width="60px">
                                    <input id="scaleFactorLandscape" class="options_outline" type="text" style="width: 40px;" value="1.0" maxlength="3">
                                </td>
                                <td width="100px">
                                    <div class="hotspot" onmouseover="tooltip.show('<strong>Float</strong> Factor to scale content images in portrait.');" onmouseout="tooltip.hide();">Factor Portrait</div>
                                </td>
                                <td width="60px">
                                    <input id="scaleFactorPortrait" class="options_outline" type="text" style="width: 40px;" value="1.0" maxlength="3">
                                </td>
                            </tr>
                            <tr>
                                <td width="100px">&nbsp;</td>
                                <td width="60px">&nbsp;</td>
                                <td width="100px">&nbsp;</td>
                                <td width="60px">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="100px">
                                    <div class="hotspot" onmouseover="tooltip.show('Fixes the item size, to the calculated size. No adjustments will be done. Images will be croped if bigger.');" onmouseout="tooltip.hide();">Fix Item Size</div>
                                </td>
                                <td width="60px">
                                    <input id="fixItemSize" class="options_outline" type="checkbox" >
                                </td>
                                <td width="100px">
                                    <div class="hotspot" onmouseover="tooltip.show('<strong>Number</strong> Maximum item height in px. If set to a value greater than \'0\' the item size will be calculated from this value instead relative to the width of the ContentFlow.');" onmouseout="tooltip.hide();">MaxItemHeight</div>
                                </td>
                                <td width="60px">
                                    <input id="maxItemHeight" class="options_outline" type="text" style="width: 40px;" value="0" maxlength="4">
                                </td>
                            </tr>
                             <tr>
                                <td width="100px">&nbsp;</td>
                                <td width="60px">&nbsp;</td>
                                <td width="100px">&nbsp;</td>
                                <td width="60px">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="100px">
                                    <div class="hotspot" onmouseover="tooltip.show('Position of item relative to it\'s coordinate.');" onmouseout="tooltip.hide();">Relative Item Position</div>
                                </td>
                                <td width="60px">
                                    <select id="relativeItemPosition" class="options_outline">
                                        <option value="top/center">Top Center</option>
                                        <option value="top/left">Top Left</option>
                                        <option value="top/right">Top Right</option>
                                        <option value="bottom/center">Bottom Center</option>
                                        <option value="bottom/left">Bottom Left</option>
                                        <option value="bottom/left">Bottom Right</option>
                                        <option value="left/center">Left Center</option>
                                        <option value="right/center">Right Center</option>
                                    </select>
                                </td>
                                <td width="100px">
                                    <div class="hotspot" onmouseover="tooltip.show('<strong>Float</strong> A flowSpeedFactor > 1 will speedup the scrollspeed, while a factor between 0 and 1 will slow it down.');" onmouseout="tooltip.hide();">Speed Factor</div>
                                </td>
                                <td width="60px">
                                    <input id="SpeedFactor" class="options_outline" type="text" style="width: 40px;" value="0.3" maxlength="3">
                                </td>
                            </tr>
                            <tr>
                                <td width="100px">&nbsp;</td>
                                <td width="60px">&nbsp;</td>
                                <td width="100px">&nbsp;</td>
                                <td width="60px">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="100px">
                                    <div class="hotspot" onmouseover="tooltip.show('<strong>Float</strong> Determines how hard it is to drag the flow.If set to \'0\' dragging of the flow is deactivated.');" onmouseout="tooltip.hide();">Drag Friction</div>
                                </td>
                                <td width="60px">
                                    <input id="drag_friction" class="options_outline" type="text" style="width: 40px;" value="1.0" maxlength="3">
                                </td>
                                <td width="100px">
                                    <div class="hotspot" onmouseover="tooltip.show('<strong>Float</strong> Scales by how many items the flow will be moved with one usage of the mousewheel.Negative values will reverse the scroll direction.If set to \'0\' scrolling with the mouse wheel is deactivated.');" onmouseout="tooltip.hide();">Wheel Speed</div>
                                </td>
                                <td width="60px">
                                    <input id="scrollWheelSpeed" class="options_outline" type="text" style="width: 40px;" value="1.0" maxlength="4">
                                </td>
                            </tr>
                        </table>
                </fieldset>
                <fieldset style="align:center; vertical-align: middle; height: 150px; width: 510px;">
                    <legend>Friends</legend>
                        <div class="friends">

                            <a href="http://www.elegantthemes.com/affiliates/idevaffiliate.php?id=12916_0_1_3" target="_blank">
                                <img class="elegantthemes" src="" alt="Original WP by ElegantThemes" width="125" height="125">
                            </a>


                            <a href="https://www.e-junkie.com/ecom/gb.php?cl=136641&c=ib&aff=211848" target="ejejcsingle">
                                <img class="themefuse" src="" alt="Original WP by ThemeFuse" width="125" height="125">
                            </a>

                            <a href="http://www.wpzoom.com/members/go.php?r=9435&i=b0" target="_blank">
                                <img  class="wpzoom" src="" alt="WPZOOM - Premium WordPress Themes" width="125" height="125">
                            </a>

                        </div>
                </fieldset>
            </div>
            <div id="content_panel" class="panel">
                <div id="tabs_number_fieldset">
                    <fieldset >
                        <?php if(!function_exists('finfo_open')) : echo '<strong>Warning:</strong>'.' You need to activate fileinfo extension.(Go to php.ini and replace ";extension=php_fileinfo.dll" with "extension=php_fileinfo.dll").'; endif;?>
                        <legend>Tabs Number</legend>
                            <input type="text" id="input_tabs_number" maxlength="2" class="text_settings" style="width: 50px;" >
                            <input type="button" value="Create" id="tabs_number" style="cursor:pointer; color: #FF0000; width: 60px; height: 20px; -webkit-border-radius: 5px;   -moz-border-radius: 5px; border-radius: 5px;">
                    </fieldset>
                </div>
                <div id="simple_accordion_hidden"></div>
                <fieldset style="align:center; vertical-align: middle; height: 155px; width: 510px; margin-top:0px; top:0px; overflow: hidden; ">
                    <legend>Friends</legend>
                        <div class="friends">

                              <a href="http://www.elegantthemes.com/affiliates/idevaffiliate.php?id=12916_0_1_3" target="_blank">
                                  <img class="elegantthemes" src="" alt="Original WP by ElegantThemes" width="125" height="125">
                              </a>

                              <a href="https://www.e-junkie.com/ecom/gb.php?cl=136641&c=ib&aff=211848" target="ejejcsingle">
                                  <img class="themefuse" src="" alt="Original WP by ThemeFuse" width="125" height="125">
                              </a>

                              <a href="http://www.wpzoom.com/members/go.php?r=9435&i=b0" target="_blank">
                                  <img class="wpzoom" src=""alt="WPZOOM - Premium WordPress Themes" width="125" height="125">
                              </a>

                        </div>
                </fieldset>
                </div>
            </div>

        <div class="mceActionPanel">
			<input type="submit" id="insert" name="insert" value="{#insert}" disabled="disabled" />
			<input type="button" id="cancel" name="cancel" value="{#cancel}" onclick="tinyMCEPopup.close();" />
			<!--<input type="button" id="apply" name="preview" value="Preview"  disabled="disabled" />-->
		</div>
        <div id="content_flow_javascript_code"></div>

</form>
</body>

</html>