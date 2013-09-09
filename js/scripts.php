<?php 
	$dirname = explode('/',dirname(__FILE__));
	if(sizeOf($dirname)==1) {
		$dirname = explode('\\',$dirname);
	}
	$copyPastaDirectory = $dirname[sizeOf($dirname)-2];
?>

<script src="<?php echo plugins_url().'/'.$copyPastaDirectory; ?>/js/jquery.cookie.js"></script>
<script>
	/* <![CDATA[ */
	jQuery(document).ready(function(e) {
		$cp_copied = [];
		try { $cp_copied = JSON.parse(jQuery.cookie('copyPasta_copied')); } catch(e) { }
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
	#copyPastaCopy span { border-left: none !important; padding-left: 0px !important; padding-right: 12px !important; }
	#copyPastaCopy span span, #copyPastaPaste span span {
		width: 7px;
		height: 23px;
		padding: 0;
		display: inline-block;
		font-size: 17px;
	}
	#copyPastaPaste span { padding-left: 0px !important; padding-right: 12px !important; }
	.copyPastaPopoverText {
		line-height: 13px; 
		font-size: 12px;
		font-weight: normal;
	}
	/** PL overrides to keep everything on one row in the toolbar **/
	#PageLinesToolbox.pl-toolbox .toolbox-handle { white-space: nowrap !important; }
	#PageLinesToolbox.pl-toolbox .toolbox-handle ul.controls > li > .btn-toolbox { padding: 0 9px !important; white-space: nowrap !important; }
	#PageLinesToolbox.pl-toolbox .toolbox-handle ul.controls { margin-left: 1px !important; white-space: nowrap !important; }
	#PageLinesToolbox.pl-toolbox .toolbox-handle ul.controls > li > .btn-toolbox.btn-closer, #PageLinesToolbox.pl-toolbox .toolbox-handle ul.controls > li > .btn-toolbox.btn-pl-toggle { padding-left: 13px !important; }
</style>