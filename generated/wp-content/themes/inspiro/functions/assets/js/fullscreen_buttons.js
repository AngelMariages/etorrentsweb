/*
 TynyMCE button for [fullscreen] shortcode
*/

(function(){
    // creates the plugin
    tinymce.create('tinymce.plugins.fullscreen', {

         init : function(ed, url, id, controlManager) {

            ed.addCommand('mceWRAP', function() {

                selection = tinyMCE.activeEditor.selection.getContent();
                tinyMCE.activeEditor.selection.setContent('[fullscreen]' + selection + '[/fullscreen]');

            });

            ed.addButton('fullscreen', {
                title : 'Fullscreen Image',
                cmd : 'mceWRAP',
                image : url+'/../image/fullscreen.png'
            });


        },

        createControl : function(n, cm) {
            return null;
        },
    });

    // registers the plugin.
    tinymce.PluginManager.add('fullscreen_btn', tinymce.plugins.fullscreen);
})()
