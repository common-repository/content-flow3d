(function() {
    tinymce.create('tinymce.plugins.Content_Flow', {
        init : function(ed, url) {
            ed.addButton('content_flow', {

                title : 'Content Flow',
				image : url+'/../images/icon.png',
                onclick : function() {

				    ed.windowManager.open({
					file : url + '/../dialog.php',
					title : "Content Flow",
					width : 560,
					height : 500 + parseInt(ed.getLang('example.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url, // Plugin absolute URL
					security_code : Math.random(), // Custom argument
                    siteurl:url
				});


				}
            });
        },
        createControl : function(n, cm) {
            return null;
        }

    });
    tinymce.PluginManager.add('content_flow', tinymce.plugins.Content_Flow);
})();
