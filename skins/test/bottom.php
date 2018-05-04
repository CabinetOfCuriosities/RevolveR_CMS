<?php 

/**
 ** RevolveR :: bottom template
 **
 **/

?>

<script src="<?php print site_host; ?>/app/revolver.js?production=1.0.8" async id="revolver">       
    
    // charging weapons with namespace
    const RevolveR_CMS = new Revolver('$');

	function renderCaptcha(captcha_new) {
		
		let pattern = captcha_new ? captcha_new : window.captcha_data;

		const pattern_id = pattern.split('*')[0];
		const pattern_0  = pattern.split('*')[1];

		window.pattern_id = pattern_id;

		const drawpane = document.querySelectorAll('#drawpane')[0];
		const sectors = document.querySelectorAll('#drawpane div');

		window.flag = false;

		for(let i of sectors) {

			i.addEventListener('click', function(e){

				e.preventDefault();

				if(!window.flag) {

					let sec = 0;

					function pad ( val ) { 
						return val > 9 ? val : "0" + val; 
					}

				}

				window.flag = true;

				let state = this.dataset.selected;

				if( state !== 'true' ) {
					this.className = 'active';
					this.dataset.selected = 'true';
				} 
				else {
					this.className = '';
					this.dataset.selected = 'false';
				}

			});

		}

		const resultpane = document.querySelectorAll('#resultpane')[0];

		if( resultpane ) {

			// drawResult 
			function drawResult(m) {

				//console.log( m );

				let context = resultpane.getContext("2d");

				function go(e, i) {

					let sectorData = e.split(':');

					let style = sectorData[0] == 1 ? "#000000" : "#90c4b8";

					context.fillStyle = style;
					context.fillRect(sectorData[1], sectorData[2], 24, 24);
					context.stroke();

				}

				m.forEach(go);

			}

			drawResult(pattern_0.split('|'));

		}
		
	}

	// Live loading
    function fetchRouteLive() {

		// animate logo
		$.dom('.revolver__header h1 a', 'style', ['color: #dcdcdc', 'display:inline-block']);
		$.dom('.revolver__main-menu ul li a', 'style', ['color: #dcdcdc', 'opacity:.1']);

    	$.dom('.revolver__header h1 a', 'animate', ['color:#913dbd:1500', 'transform: scale(.5,.5,.5) rotate(360deg,360deg,360deg):1500:bounce']);
    	$.dom('.revolver__header h1 a', 'animate', ['color:#913dbd:1500', 'transform: scale(1,1,1):2000:elastic']);

    	$.dom('.revolver__main-menu ul li a ', 'animate', ['color:#8b69dc:2500', 'opacity:1:3500:bounce']);


		$.fetch('/secure.php','GET','json', function(){ 
			renderCaptcha( this.key );
		}); 

		$.event('a', 'click', function(e) {

			e.preventDefault();
			getPageLive(e.target.href, e.target.text);

		});

		$.event('.external a', 'click', function(e) {
			window.open(e.target.href);
		});

		$.event('input[type="submit"]', 'click', function() {

			const sectors = $.dom('#drawpane div');

			if( window.flag ) {

				let matrix = [];
				let counter = 0;

				for( let a of sectors ) {

					let coords = a.dataset.xy;
					let state = a.dataset.selected === 'true' ? 1 : 0;

					matrix[counter] = state +':'+ coords;
					counter++;

				}

				const patterns_match = matrix.join('|');

				$.attr('input[name="revolver_captcha"]', {'value': window.pattern_id +'*'+ patterns_match } );

			}

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

		// history routes
		window.onpopstate = function(e) {
			getPageLive(e.state.url, e.state.title);
		}

    }

    // run live
    fetchRouteLive();

</script>

<?php 

?>