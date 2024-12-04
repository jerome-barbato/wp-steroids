;(function($) {

	var allowInterfaceResizeInterval;

	function disableACFLayoutReorder(){
		$('.acf-flexible-content > .values').sortable( "disable" );
		$('.acf-flexible-content .ui-sortable-handle').removeAttr( "title" );
	}

	function determineIfSidebarOpen(){

		var sidebar_enabled = false;

		$('.interface-pinned-items button').each(function(){
			if($(this).hasClass('is-pressed')){
				sidebar_enabled = true;
			}
		})

		if(sidebar_enabled)
			$('.edit-post-layout, .edit-site-layout').addClass('is-sidebar-opened');
		else
			$('.edit-post-layout, .edit-site-layout').removeClass('is-sidebar-opened');
	}

	function allowInterfaceResize(){

		let $sidebar = $('.interface-interface-skeleton__sidebar');
		if( $sidebar.length ) {

			clearInterval(allowInterfaceResizeInterval);

			$sidebar.width(localStorage.getItem('personal_sidebar_width'))
			$sidebar.resizable({
				handles: 'w',
				resize: function (event, ui) {
					$(this).css({'left': 0});
					localStorage.setItem('personal_sidebar_width', $(this).width());
				}
			});

			$('body').on('click', '.interface-pinned-items button', determineIfSidebarOpen);
			$('body').on('click', '.interface-complementary-area-header .components-button', determineIfSidebarOpen);

			determineIfSidebarOpen();
		}
	}

	function ucfirst(string) {
		return string.charAt(0).toUpperCase() + string.slice(1);
	}

	function watchDataChanges(){

		let editor = wp.data.select('core/editor');

		let post = {
			post_title: editor.getEditedPostAttribute('title'),
			post_excerpt: editor.getEditedPostAttribute('excerpt'),
			thumbnail : editor.getEditedPostAttribute('featured_media')
		}

		wp.data.subscribe(() => {

			let data = {
				post_title: editor.getEditedPostAttribute('title'),
				post_excerpt: editor.getEditedPostAttribute('excerpt'),
				thumbnail_id : editor.getEditedPostAttribute('featured_media')
			}

			if( JSON.stringify(data) !== JSON.stringify(post) ){

				post = data;
				let blocks = wp.data.select( 'core/block-editor' ).getBlocks();

				if( blocks.length ){

					let block = blocks[0];
					let data = block.attributes.data ?? {}
					data.post = post;

					wp.data.dispatch('core/block-editor').updateBlockAttributes(block.clientId, data)
				}
			}
		});

		//remove block_editor_style-css from main editor if iFrame is enabled
		//Todo: find a better way to detect iFrame
		if( document.querySelector('[name="editor-canvas"]') ){

			let style = document.getElementById('block_editor_style-css')

			if( style ){

				let head = document.getElementsByTagName('head')[0];
				head.removeChild(style)
			}
		}
	}

	function initTranslation(){

		$('#edittag .term-name-wrap td, #edittag .term-slug-wrap td, #edittag .term-description-wrap td, #wp-content-wrap, #titlewrap, #wp-advanced_description-wrap, #postexcerpt .inside, #menu-to-edit .menu-item-settings label, #link-selector .wp-link-text-field label, .edit-post-visual-editor__post-title-wrapper').append('<a class="wps-translate wps-translate--'+wps.enable_translation+'" title="Translate with '+ucfirst(wps.enable_translation)+'"></a>')
		$('#tag-post-content #name').wrap('<div class="input-wrapper"></div>')
		$('#tag-post-content #name').after('<a class="wps-translate wps-translate--'+wps.enable_translation+'" title="Translate with '+ucfirst(wps.enable_translation)+'"></a>')
		$('#menu-to-edit span.description').remove()

		$(document).on('mouseenter', '.editor-post-excerpt', function (){

			if( !$(this).find('.wps-translate').length )
				$(this).find('.components-base-control__field').append('<a class="wps-translate wps-translate--'+wps.enable_translation+'" title="Translate with '+ucfirst(wps.enable_translation)+'"></a>')
		})

		$(document).on('click', '.wps-translate', function (){

			var $self = $(this);

			var is_title = $(this).prev('.editor-post-title').length;
			var is_excerpt = $(this).closest('.editor-post-excerpt').length;

			if( !is_title && !is_excerpt ){

				var $inputs = $(this).parent().find('.acf-input-wrap > input, > textarea, textarea.wp-editor-area, .field, #title, #name, #excerpt')

				if( !$inputs.length ){

					$inputs = $(this).prev('input, textarea')

					if( !$inputs.length ){

						$inputs = $(this).prev().prev('input, textarea')

						if( !$inputs.length )
							return;
					}
				}

				var $input = $inputs.first()
				var $editable = $input.prev('[contenteditable]');
				var is_editor = $input.hasClass('wp-editor-area');
			}

			var value = ''

			if( is_title )
				value = wp.data.select( 'core/editor' ).getEditedPostAttribute( 'title' );
			else if (is_excerpt )
				value = wp.data.select( 'core/editor' ).getEditedPostAttribute( 'excerpt' );
			else
				value = is_editor ? tinymce.editors[$input.attr('id')].getContent() : $input.val();

			if( value.length <= 2)
				return;

			$self.addClass('loading')

			$.post(wps.ajax_url, {action: 'translate', q:value, format:(is_editor?'html':'text')}, function (response){

				$self.removeClass('loading')

				if( response.text.length ){

					var translations = response.text;

					if( is_title ){

						wp.data.dispatch( 'core/editor' ).editPost( { title: translations } );
					}
					else if( is_excerpt ){

						wp.data.dispatch( 'core/editor' ).editPost( { excerpt: translations } );
					}
					else if( is_editor ){

						tinymce.editors[$input.attr('id')].setContent(translations)
					}
					else{

						if( $editable.length )
							$editable.html(translations)

						$input.val(translations).change()
					}
				}
			}).fail(function(response) {

				alert( response.message )
				$self.removeClass('loading')
			})
		})
	}

	$(document).ready(function(){

		allowInterfaceResizeInterval = setInterval(allowInterfaceResize, 500);

		if( $('body').hasClass('no-acf_edit_layout') ){

			disableACFLayoutReorder();
			setInterval(disableACFLayoutReorder, 1000);
		}

		$('.postbox-container [data-wp-lists]').each(function(){

			if( $(this).find('.children').length )
				$(this).addClass('has-children');

			$(this).find('input[type="checkbox"]').click(function(){

				if( !$(this).is(':checked') )
					$(this).closest('li').find('input[type="checkbox"]').attr('checked', false)
				else
					$(this).parents('li').find('> label input[type="checkbox"]').attr('checked', true)
			})
		})

		$('.acf-label').each(function(){

			if( $(this).text().length < 2 )
				$(this).remove()
		})

		$('#submit-columndiv').click(function(){

			$( '.columndiv .spinner' ).addClass( 'is-active' );

			let menu = {
				'-1': {
					'menu-item-type': 'custom',
					'menu-item-url': $('.url-columndiv').val(),
					'menu-item-title': $('.title-columndiv').val()
				}
			};

			window.wpNavMenu.addItemToMenu( menu, window.wpNavMenu.addMenuItemToBottom, function() {
				// Remove the Ajax spinner.
				$( '.columndiv .spinner' ).removeClass( 'is-active' );
			});
		})

		$('#wp-admin-bar-build a').click(function(e){

			e.preventDefault();
			var $el = $(this);

			$el.addClass('loading');

			$.get( $el.attr('href') ).then(function (){

				var refresh = setInterval(function (){

					$('#wps-build-badge').attr('src', $('#wps-build-badge').data('url')+'&v='+Date.now())

				}, 1000);

				setTimeout(function (){

					clearInterval(refresh);
					$el.removeClass('loading');

				}, 10000)
			})
		})
	});

	$(window).load(function() {

		if( wps.enable_translation )
			initTranslation();

		if( typeof wp != 'undefined' && typeof wp.data != 'undefined' )
			watchDataChanges()
	});

})(jQuery);
