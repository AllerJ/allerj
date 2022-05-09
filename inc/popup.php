<?php
add_action('wp_footer', 'dash_popup');
function dash_popup(){
?>

<div class="hover_bkgr_fricc" id="dash-popup" style='display:none'>
	<span class="helper"></span>
	<div>
		<div class="popupCloseButton">&times;</div>
		<?php	
			ea_block_area()->show( 'popup' );
		?>
	</div>
</div>

<script type="text/javascript">
	jQuery(window).load(function () {

		jQuery('.hover_bkgr_fricc').click(function(){
			jQuery('.hover_bkgr_fricc').hide();
		});
		jQuery('.popupCloseButton').click(function(){
			jQuery('.hover_bkgr_fricc').hide();
		});
	});
	
	function PopUp(hideOrshow) {
		if (hideOrshow == 'hide') {
			document.getElementById('dash-popup').style.display = "none";
		}
		else  if(localStorage.getItem("popupWasShown") == null) {
			localStorage.setItem("popupWasShown",1);
			document.getElementById('dash-popup').removeAttribute('style');
		}
	}
	window.onload = function () {
		setTimeout(function () {
			PopUp('show');
		}, 0);
	}
	
	
	function hideNow(e) {
		if (e.target.id == 'dash-popup') document.getElementById('dash-popup').style.display = 'none';
	}
	
	
</script>
<?php
};