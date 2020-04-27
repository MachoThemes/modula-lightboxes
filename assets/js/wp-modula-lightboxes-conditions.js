(function( Modula ){
    "use strict"
    console.log( wp.Modula)
var modulaLightboxesConditions = Backbone.Model.extend({

    initialize: function( args ){

		var rows = jQuery('.modula-settings-container tr[data-container]');
		var tabs = jQuery('.modula-tabs .modula-tab');
		this.set( 'rows', rows );
		this.set( 'tabs', tabs );

		this.initEvents();
		this.initValues();

    },

     initValues: function(){
         this.changedLightbox(false,wp.Modula.Settings.get('lightbox'));
     },

    initEvents: function(){

        this.listenTo(wp.Modula.Settings, 'change:lightbox', this.changedLightbox );
     },

    changedLightbox: function( settings, value ){
        var rows = this.get( 'rows' ),
            tabs = this.get( 'tabs' )

        if ( 'fancybox' == value ) {
			rows.filter( '[data-container="use-fancybox"]').hide();
        }else{
			rows.filter( '[data-container="use-fancybox"]').show();
        }
    },

});

jQuery( document ).ready( function(){
    new modulaLightboxesConditions();
})

}( jQuery, wp.Modula))


