<?php
/**
 * Helper Go Back Url
 *
 * Print an anchor link to previous page
 */
class Zend_View_Helper_PrintGoBackUrl extends Zend_View_Helper_Abstract 
{
    public function printGoBackUrl($url) 
    {
		?>
		<div class="flat_area grid_16">
			<a href="<?php echo $url; ?>" class="roundall_grey back_icon float-right">
				<span class="ml22">Kembali</span>
			</a>
		</div>
		<?php
    }
}