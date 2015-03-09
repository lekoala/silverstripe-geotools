<div id="$ID" class="$CSSClasses" 
	 data-lat="$Latitude" data-lon="$Longitude"
	 <% if Icon %>data-icon="$Icon"<% end_if %>
	 data-tilelayer="$TileLayer"
	 data-tileoptions='$TileOptionsJson'
	 data-mapoptions='$MapOptionsJson'
	 data-builderoptions='$BuilderOptionsJson'
	 data-itemsurl="$ItemsUrl"
	 style="<% if Width %>width:$Width;<% end_if %><% if Height %>height:$Height;<% end_if %>"></div>

<% if Content %>
<div id="{$ID}_content" style="display:none">$Content</div>
<% end_if %>
