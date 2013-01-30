<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title></title>
    <script type="text/javascript" src="scripts/jquery-1.3.2.js"></script>
    <script type="text/javascript" src="scripts/jquery-ui-1.7.2.custom.min.js"></script>
    <link rel="Stylesheet" type="text/css" href="style/jqueryui/ui-lightness/jquery-ui-1.7.2.custom.css" />

    <script type="text/javascript" src="scripts/jHtmlArea-0.7.5.js"></script>
    <link rel="Stylesheet" type="text/css" href="style/jHtmlArea.css" />
    
    <style type="text/css">
        /* body { background: #ccc;} */
        div.jHtmlArea .ToolBar ul li a.custom_disk_button 
        {
            background: url(images/disk.png) no-repeat;
            background-position: 0 0;
        }
        
        div.jHtmlArea { border: solid 1px #ccc; }
    </style>
</head>
<body>
    <script type="text/javascript">    
        // You can do this to perform a global override of any of the "default" options
        // jHtmlArea.fn.defaultOptions.css = "jHtmlArea.Editor.css";

        $(function() {
            //$("textarea").htmlarea(); // Initialize all TextArea's as jHtmlArea's with default values

            $("#txtDefaultHtmlArea").htmlarea(); // Initialize jHtmlArea's with all default values

            $("#txtCustomHtmlArea").htmlarea({
                // Override/Specify the Toolbar buttons to show
                toolbar: [
                    ["bold", "italic", "underline", "|", "forecolor"],
                    ["p", "h1", "h2", "h3", "h4", "h5", "h6"],
                    ["link", "unlink", "|", "image"],                    
                    [{
                        // This is how to add a completely custom Toolbar Button
                        css: "custom_disk_button",
                        text: "Save",
                        action: function(btn) {
                            // 'this' = jHtmlArea object
                            // 'btn' = jQuery object that represents the <A> "anchor" tag for the Toolbar Button
                            alert('SAVE!\n\n' + this.toHtmlString());
                        }
                    }]
                ],

                // Override any of the toolbarText values - these are the Alt Text / Tooltips shown
                // when the user hovers the mouse over the Toolbar Buttons
                // Here are a couple translated to German, thanks to Google Translate.
                toolbarText: $.extend({}, jHtmlArea.defaultOptions.toolbarText, {
                        "bold": "fett",
                        "italic": "kursiv",
                        "underline": "unterstreichen"
                    }),

                // Specify a specific CSS file to use for the Editor
                css: "style//jHtmlArea.Editor.css",

                // Do something once the editor has finished loading
                loaded: function() {
                    //// 'this' is equal to the jHtmlArea object
                    //alert("jHtmlArea has loaded!");
                    //this.showHTMLView(); // show the HTML view once the editor has finished loading
                }
            });
        });
    </script>
    
    <textarea id="txtDefaultHtmlArea" cols="150" rows="100"><p><h3>Test H3</h3>This is some sample text to test out the <b>WYSIWYG Control</b>.</p></textarea>
    <input type="button" value="Alert HTML" onclick="alert($('#txtDefaultHtmlArea').htmlarea('html'));" />
    <input type="button" value="Set HTML" onclick="$('#txtDefaultHtmlArea').htmlarea('html', 'Some <strong>HTML</strong> value.')" />
    <input type="button" value="Change Color to Blue" onclick="$('#txtDefaultHtmlArea').htmlarea('forecolor', 'blue');" />
    
    <br /><hr /><br />
    </body>
</html>
