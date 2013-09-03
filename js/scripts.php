<script>
/*!
 * jQuery Cookie Plugin v1.3.1
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2013 Klaus Hartl
 * Released under the MIT license
 */
(function (factory) {
	if (typeof define === 'function' && define.amd) {
		// AMD. Register as anonymous module.
		define(['jquery'], factory);
	} else {
		// Browser globals.
		factory(jQuery);
	}
}(function ($) {

	var pluses = /\+/g;

	function decode(s) {
		if (config.raw) {
			return s;
		}
		return decodeURIComponent(s.replace(pluses, ' '));
	}

	function decodeAndParse(s) {
		if (s.indexOf('"') === 0) {
			// This is a quoted cookie as according to RFC2068, unescape...
			s = s.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, '\\');
		}

		s = decode(s);

		try {
			return config.json ? JSON.parse(s) : s;
		} catch(e) {}
	}

	var config = $.cookie = function (key, value, options) {

		// Write
		if (value !== undefined) {
			options = $.extend({}, config.defaults, options);

			if (typeof options.expires === 'number') {
				var days = options.expires, t = options.expires = new Date();
				t.setDate(t.getDate() + days);
			}

			value = config.json ? JSON.stringify(value) : String(value);

			return (document.cookie = [
				config.raw ? key : encodeURIComponent(key),
				'=',
				config.raw ? value : encodeURIComponent(value),
				options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
				options.path    ? '; path=' + options.path : '',
				options.domain  ? '; domain=' + options.domain : '',
				options.secure  ? '; secure' : ''
			].join(''));
		}

		// Read
		var cookies = document.cookie.split('; ');
		var result = key ? undefined : {};
		for (var i = 0, l = cookies.length; i < l; i++) {
			var parts = cookies[i].split('=');
			var name = decode(parts.shift());
			var cookie = parts.join('=');

			if (key && key === name) {
				result = decodeAndParse(cookie);
				break;
			}

			if (!key) {
				result[name] = decodeAndParse(cookie);
			}
		}

		return result;
	};

	config.defaults = {};

	$.removeCookie = function (key, options) {
		if ($.cookie(key) !== undefined) {
			// Must not alter options, thus extending a fresh object...
			$.cookie(key, '', $.extend({}, options, { expires: -1 }));
			return true;
		}
		return false;
	};

}));
</script>
<script>
	/* <![CDATA[ */
	jQuery(document).ready(function(e) {
		$cp_copied = JSON.parse(jQuery.cookie('copyPasta_copied'));
		// new instance of the container 
		jQuery('div.toolbox-handle')
			.first()
			.append('<ul id="copyPasta" class="unstyled controls"><li id="copyPastaCopy"><span class="type-btn btn-toolbox" data-original-title="Copy"><span><i class="icon-copy"></i></span></li><li id="copyPastaPaste"><span class="type-btn btn-toolbox" data-original-title="Paste"><span><i class="icon-paste"></i></span></span></li></ul>');
		//
		function $cp_hidePopovers() {
			jQuery('#copyPastaCopy').popover('destroy');
			jQuery('#copyPastaPaste').popover('destroy');
		}
		//
		jQuery('#copyPastaCopy').off('click').on('click', function(event) {
			event.preventDefault();
			var $data = [], 
				$type = $local = '',
				$options = jQuery('div.panel-section-options[data-key="section-options"] div#local'),
				$clone_id = $options.attr('data-clone'),
				$inner = jQuery('input[name^="'+$clone_id+'"],select[name^="'+$clone_id+'"],textarea[name^="'+$clone_id+'"]', $options);
			
			if($options.parent().css('display')!=='none') {			
				if($inner.length>0) {
					$inner.each(function(i,e) { 
						var $e = jQuery(e),
							$id = $e.attr('id');
						$data.push( [(($id!==undefined && $id!=="") ? $id : $e.attr('name').replace($clone_id,'').replace('[','').replace(']','')), ((e.localName=='input') ? e.type : e.localName), $e.attr('value')] );
					});
					$cp_copied = $data;
					jQuery.cookie('copyPasta_copied',JSON.stringify($cp_copied));
				} else {
					jQuery(this).popover({title:'CopyPasta Error', content:'<span class="copyPastaPopoverText">This panel is not a section panel. CopyPasta only transfers data from one section to another.</span>', html:true, placement:'bottom'}).popover('show');
					setTimeout($cp_hidePopovers, 4000);
				}
			} else {
				jQuery(this).popover({title:'CopyPasta Error', content:'<span class="copyPastaPopoverText">This panel is not a section panel. CopyPasta only transfers data from one section to another.</span>', html:true, placement:'bottom'}).popover('show');
				setTimeout($cp_hidePopovers, 4000);
			}
		});
		jQuery('#copyPastaPaste').off('click').on('click', function(event) {
			event.preventDefault();
			if($cp_copied.length>0) {
				var $options = jQuery('div.panel-section-options[data-key="section-options"] div#local'),
					$clone_id = $options.attr('data-clone'),
					$validtarg = null,
					$inner = jQuery('input[name^="'+$clone_id+'"],select[name^="'+$clone_id+'"],textarea[name^="'+$clone_id+'"]', $options);
				if($inner.length>0) {
					for($i=0;$i<$cp_copied.length;$i++) {
						var $data = $cp_copied[$i];
						if($data[1]=='text'||$data[1]=='hidden'||$data[1]=='checkbox') {
							$data[1] = 'input';
						}
						$targ = jQuery( $data[1]+'[name="'+$clone_id+'['+$data[0]+']"]' );
						if($targ.length>0) {
							$targ.attr('value',$data[2]);
							$validtarg = $targ;
						}
					};
					if($validtarg!==null) {
						$validtarg.focus().blur();
					} else {
						jQuery(this).popover({title:'CopyPasta Error', content:'<span class="copyPastaPopoverText">Data in clipboard does not match current panel.</span>', html:true, placement:'bottom'}).popover('show');
						setTimeout($cp_hidePopovers, 4000);
					}
				} else {
					jQuery(this).popover({title:'CopyPasta Error', content:'<span class="copyPastaPopoverText">This panel is not a section panel. CopyPasta only transfers data from one section to another.</span>', html:true, placement:'bottom'}).popover('show');
					setTimeout($cp_hidePopovers, 4000);
				}
			} else {
				jQuery(this).popover({title:'Nothing to paste', content:'<span class="copyPastaPopoverText">Nothing to paste, the clipboard is empty.</span>', html:true, placement:'bottom'}).popover('show');
				setTimeout($cp_hidePopovers, 4000);
			}
		});
		//--
	});
	/* ]]> */
</script>
<style>
	#copyPasta {
		border-left: none !important;
		margin-left: 0 !important;
	}
	#copyPastaCopy span { border-left: none !important; padding-left: 6px !important; padding-right: 12px !important; }
	#copyPastaCopy span span, #copyPastaPaste span span {
		width: 7px;
		height: 23px;
		padding: 0;
		display: inline-block;
		font-size: 17px;
	}
	#copyPastaPaste span { padding-left: 6px !important; padding-right: 12px !important; }
	.copyPastaPopoverText {
		line-height: 13px; 
		font-size: 12px;
		font-weight: normal;
	}
</style>