/**
 * Theme Customizer enhancements for a better user experience.
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

	if( 'undefined' == typeof hootInlineStyles )
		window.hootInlineStyles = [ 'hoot-style', {}, '' ];

	/*** Utility ***/

	function hootUpdateCss( setting, value, append ) {
		var $target = $( '#hoot-customize-' + setting );
		if ( $target.length )
			if ( append !== undefined ) $target.append( value ); else $target.html( value );
	}
	function hootcolor(col, amt) { // @credit: https://css-tricks.com/snippets/javascript/lighten-darken-color/
		var usePound = false;
		if (col[0] == "#") { col = col.slice(1); usePound = true; }
		var num = parseInt(col,16);
		var r = (num >> 16) + amt; if (r > 255) r = 255; else if  (r < 0) r = 0;
		var b = ((num >> 8) & 0x00FF) + amt; if (b > 255) b = 255; else if  (b < 0) b = 0;
		var g = (num & 0x0000FF) + amt; if (g > 255) g = 255; else if (g < 0) g = 0;
		return (usePound?"#":"") + (g | (b << 8) | (r << 16)).toString(16);
	}
	var hootpload = hootInlineStyles[2];
	function hootBuildCss( index, newval ){
		var css = '', csspart = '', selectors, media, mselectors, selector;
		if ( $.isArray( hootInlineStyles ) && hootInlineStyles[1] && typeof hootInlineStyles[1] == 'object' && hootInlineStyles[1][index] && newval ) {
			for(var prop in hootInlineStyles[1][index]){
				if (!hootInlineStyles[1][index].hasOwnProperty(prop)) continue;
				selectors = hootInlineStyles[1][index][prop];
				if( prop == 'media' ){
					for(var query in selectors){
						if (!selectors.hasOwnProperty(query)) continue;
						media = selectors[query];
						css += query+'{';
						for(var mprop in media){
							if (!media.hasOwnProperty(mprop)) continue;
							mselectors = media[mprop];

							csspart = '';
							for(var mskey in mselectors){
								if (!mselectors.hasOwnProperty(mskey)) continue;
								mselector = mselectors[mskey];
								csspart += mselector + ',';
							}
							if(csspart) css += csspart.replace(/(^,)|(,$)/g, "") + '{'+mprop+':'+newval+'}';

						}
						css += '}';
					}
				} else {

					csspart = '';
					for(var skey in selectors){
						if (!selectors.hasOwnProperty(skey)) continue;
						selector = selectors[skey];
						csspart += selector + ',';
					}
					if(csspart) css += csspart.replace(/(^,)|(,$)/g, "") + '{'+prop+':'+newval+'}';

				}
			}
		}
		return css;
	}
	function hootHexToRgb(hex) { //@credit: https://stackoverflow.com/questions/5623838/rgb-to-hex-and-hex-to-rgb
		// Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
		var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
		hex = hex.replace(shorthandRegex, function(m, r, g, b) {
			return r + r + g + g + b + b;
		});
		var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
		return result ? {
			r: parseInt(result[1], 16),
			g: parseInt(result[2], 16),
			b: parseInt(result[3], 16)
			} : null;
	}

	/** Theme Settings **/

	wp.customize( 'widgetmargin', function( value ) {
		value.bind( function( newval ) {
			var newvalint = parseInt(newval);
			if ( !newvalint || isNaN( newvalint ) ) newvalint = '';
			if( newvalint ) {
				var newvalintsmall = newvalint / 2;
				newvalintsmall = ( newvalintsmall > 25 ) ? newvalintsmall : 25;
				$('.main > .main-content-grid:first-child , .content-frontpage > .frontpage-area-boxed:first-child').css('margin-top', newvalintsmall+'px');
			}
		} );
	} );

	if(!hootpload){ wp.customize( 'accent_color', function( value ) {
		value.bind( function( newval ) {
			newval = hootHexToRgb(newval);
			if ( newval ) $('#topbar').css('background-color','rgba('+newval.r+','+newval.g+','+newval.b+',0.15)');
		} );
	} ); }

	if(!hootpload){ wp.customize( 'subheadings_fontface_style', function( value ) {
		value.bind( function( newval ) {
			var css = '', cssvalue = '';

			cssvalue = (newval=='uppercase' || newval=='uppercasei') ? 'uppercase' : 'none';
			css = hootBuildCss( 'subheadings_fontface_style_trans', cssvalue );
			hootUpdateCss( 'subheadings_fontface_style_trans', css );

			cssvalue = (newval=='standardi' || newval=='uppercasei') ? 'italic' : 'normal';
			css = hootBuildCss( 'subheadings_fontface_style', cssvalue );
			hootUpdateCss( 'subheadings_fontface_style', css );
		} );
	} ); }

} )( jQuery );