<?php

// Restricting Direct Access
defined('ABSPATH') or die(require_once('404.php'));

?>
<!-- Wrapper -->
<div class='<?php echo $this->__('wrapper'); ?>'>

	<div class='<?php echo $this->__('header'); ?>' >
		<h3><?php echo $this->name; ?></h3>
	</div>

	<div class='<?php echo $this->__('content'); ?>' >

		<table>

			<tr>
				<td>Today</td>
				<td><?php echo $today ;?></td>
			</tr>

			<tr>
				<td>Yesterday</td>
				<td><?php echo $yesterday ;?></td>
			</tr>

			<tr>
				<td>Last Week</td>
				<td><?php echo $last_week ;?></td>
			</tr>

			<tr>
				<td>Total</td>
				<td><?php echo $total ;?></td>
			</tr>

		</table>

	</div>

	<div class='<?php echo $this->__('footer'); ?>' >

	</div>

</div>
<!-- /Wrapper -->
