tinymce.init({

        theme : "modern",

        selector : "textarea.mceEditor",

		height: 300,

		plugins: [

         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",

         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",

         "save table contextmenu directionality emoticons template paste textcolor jbimages"

   		],

		toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages | print preview media fullpage | forecolor backcolor emoticons", 

		relative_urls: false,
		
		forced_root_block : '',

		onchange_callback: function(editor){

				tinyMCE.triggerSave();

				jQuery("#" + editor.id).valid();

		},

		setup: function(ed){

            ed.on('blur', function() {

                jQuery("#" + ed.id).html( tinyMCE.activeEditor.getContent() );

            });

        }

});



tinymce.init({

        selector : "textarea.mceSimpleEditor",

		onchange_callback: function(editor){

				tinyMCE.triggerSave();

				jQuery("#" + editor.id).valid();

		},

		forced_root_block : '',
		
		setup: function(ed){

            ed.on('blur', function() {

                jQuery("#" + ed.id).html( tinyMCE.activeEditor.getContent() );

            });

        }

});