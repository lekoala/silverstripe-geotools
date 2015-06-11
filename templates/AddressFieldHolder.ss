<div <% if $Name %>id="$Name"<% end_if %> class="field <% if $extraClass %>$extraClass<% end_if %>" style="margin-bottom:0px;padding-bottom:0px">
	<% if $Title %><label class="left">$Title</label><% end_if %>
	
	<div class="middleColumn fieldgroup<% if $Zebra %> fieldgroup-$Zebra<% end_if %>" style="padding:0px">
		<% loop $FieldList %>
			<div class="fieldgroup-field $FirstLast $EvenOdd" style="padding-top:0px;padding-bottom:0px">
				$SmallFieldHolder
			</div>
		<% end_loop %>
	</div>
	<% if $RightTitle %><label class="right">$RightTitle</label><% end_if %>
	<% if $Message %><span class="message $MessageType">$Message</span><% end_if %>
	<% if $Description %><span class="description">$Description</span><% end_if %>
</div>