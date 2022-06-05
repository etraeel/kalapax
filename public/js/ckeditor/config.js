/**
 * @license Copyright (c) 2003-2020, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
    config.extraPlugins = 'lineheight';
    config.line_height="1em;1.1em;1.2em;1.3em;1.4em;1.5em;1.7em;1.9em;2em;2.2em;2.5em;3em;" ;

    //config.contentsCss = 'http://www.wilsea.com/ckeditor2/fonts/fonts.css';
    config.contentsCss = 'http://fonts.googleapis.com/css?family=Lobster';
    config.contentsCss = 'http://fonts.googleapis.com/css?family=Cardo:400,400italic,700';

    config.contentsCss = '/css/AllFonts.css';

    config.extraPlugins='video';

    config.extraPlugins = 'html5video,widget,widgetselection,clipboard,lineutils';
    //the next line add the new font to the combobox in CKEditor
    //config.font_names =  'Hoefler Text/Hoefler Text;'+config.font_names;
    config.font_names =  'serif;sans serif;Yekan/Yekan;Vazir/Vazir;IRANSans/IRANSans;monospace;cursive;fantasy;Lobster;';


};
