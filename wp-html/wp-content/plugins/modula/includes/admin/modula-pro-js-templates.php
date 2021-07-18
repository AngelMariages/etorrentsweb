<script type="text/html" id="tmpl-modula-filters">
    <label class="" for="modula-pro-filters">
        <span class="name"><?php esc_html_e( 'Filters', 'modula-pro' ); ?></span>
        <input type="text" name="filters" id="modula-pro-filters" value="{{data.filters}}">
    </label>
</script>

<script type="text/html" id="tmpl-modula-bulkedit">
	<div class="media-frame-title"><h1><?php esc_html_e( 'Bulk Edit', 'modula-pro' ) ?></h1></div>
	<div class="media-frame-menu">
		<div class="media-menu">
			<a id="modula-select-all" href="javascript:void(0);" class="media-menu-item"><?php esc_html_e( 'Select All', 'modula-pro' ) ?></a>
			<a id="modula-deselect-all" href="javascript:void(0);" class="media-menu-item "><?php esc_html_e( 'Deselect All', 'modula-pro' ) ?></a>
			<a id="modula-toggle" href="javascript:void(0);" class="media-menu-item"><?php esc_html_e( 'Toggle selection', 'modula-pro' ) ?></a>
		</div>
	</div>
	<div class="media-frame-content" data-columns="7">
		<div class="attachments-browser">
			<ul tabindex="-1" class="attachments"></ul>
			<div class="media-sidebar"></div>
		</div>
	</div>
	<div class="media-frame-toolbar">
		<div class="media-toolbar">
			<div class="media-toolbar-primary search-form">
				<button id="delete-modula-bulkedit" type="button" class="button media-button button-link-delete button-large media-button-gallery"><?php esc_html_e( 'Delete', 'modula-pro' ) ?></button>
				<button id="save-modula-bulkedit" type="button" class="button media-button button-primary button-large media-button-gallery"><?php esc_html_e( 'Save', 'modula-pro' ) ?></button>
				<button id="close-modula-bulkedit" type="button" class="button media-button button-large media-button-gallery"><?php esc_html_e( 'Save & Close', 'modula-pro' ) ?></button>
			</div>
		</div>
	</div>
</script>

<script type="text/html" id="tmpl-modula-bulkedit-item">
	<div class="attachment-preview js--select-attachment type-image {{data.orientation}}">
		<div class="thumbnail">
			<div class="centered">
				<img src="{{data.thumbnail}}" draggable="false" alt="">
			</div>
		</div>
	</div>
	<button type="button" class="check" tabindex="-1"><span class="media-modal-icon"></span><span class="screen-reader-text"><?php esc_html_e( 'Deselect', 'modula-pro' ) ?></span></button>
</script>