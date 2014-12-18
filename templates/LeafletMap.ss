<div id="$ID" class="$CSSClasses" 
	 data-lat="$Latitude" data-lon="$Longitude" 
	 data-tilelayer="$TileLayer"
	 data-tileoptions='$TileOptionsJson'
	 data-mapoptions='$MapOptionsJson'
	 style="<% if Width %>width:$Width;<% end_if %><% if Height %>height:$Height;<% end_if %>"></div>

<% if Content %>
<div id="{$ID}_content" style="display:none">$Content</div>
<% end_if %>
