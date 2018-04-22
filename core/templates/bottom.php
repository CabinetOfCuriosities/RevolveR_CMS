<?php 

/**
 ** RevolveR :: bottom template
 **
 **/

?>

<script src="<?php print site_host; ?>/app/revolver.js?production=1.0.8" async id="revolver">       
    
    // charging weapons with namespace
    const RevolveR_CMS = new Revolver('$');
    $.storage(['Revolver=1.0.8'],'set');

    function fetchRouteLive() {

		$.event('a', 'click', function(e) {

			e.preventDefault();
			getPageLive(e.target.href, e.target.text);

		});

		$.event('.external a', 'click', function(e) {
			window.open(e.target.href);
		});


		// full dynamic forms
		$.fetchSubmit('form', 'text', function() {

			$.dom('#RevolverRoot')[0].innerHTML = '';
			
			for( let i of $.convertSTRToHTML(this) ) {

				if( i.tagName === 'TITLE' ) {
					var title = i.innerHTML;
				}
				if ( i.id === 'RevolverRoot' ) {
					var shell = i.innerHTML;
				}
				// snizzy hack
				if( i.tagName === 'META') {
					if( i.name === 'host') {
						eval( 'window.route="'+ i.content +'";' );
					}
				}
			}

			$.insert($.dom('#RevolverRoot'), shell);
			
			$.location(title, route);
			
			$.scroll();

			fetchRouteLive();

		});


		// full dynamic fetch router
		function getPageLive(url, title) {

			$.fetch(url, 'GET', 'html', function(){

				$.dom('#RevolverRoot')[0].innerHTML = '';
				
				for( let i of $.convertSTRToHTML(this) ) {

					if( i.tagName === 'TITLE' ) {
						var title = i.innerHTML;
					}
					if ( i.id === 'RevolverRoot' ) {
						var shell = i.innerHTML;
					}

					// snizzy hack
					if( i.tagName === 'META') {
						if( i.name === 'host') {
							eval( 'window.route="'+ i.content +'";' );
						}
					}
				}

				$.insert($.dom('#RevolverRoot'), shell);
				
				$.location(title, route);
				
				$.scroll();

				fetchRouteLive();
			});
		}    	
    }

    // run live
    fetchRouteLive();
</script>

<?php 

?>