<div class='container-fluid'>

	<div class='row-fluid'>

		<div class='xs-col-12 sm-col-12 md-col-6 lg-col-6 text-center page-header'>
			<h3 class=''><?php echo $this->name; ?></h3>
		</div>

		<div class='xs-col-12 sm-col-12 md-col-6 lg-col-6 text-center'>
			<?php $this->save_admin_form_data(); ?>
		</div>



		<div class='xs-col-12 sm-col-12 md-col-6 lg-col-6'>

			<form class='form-inline'method='post' action=''>

				<div class="form-group">
					<label for="<?php echo $this->__('count-admin');?>">Count Admin View : </label>
					<input type="checkbox" class="form-control" id="<?php echo $this->__('count-admin');?>" name='<?php echo $this->__('count-admin');?>' <?php if(get_option($this->__('count-admin')) == 'on') echo 'checked';?> >
					<p class="help-block">If this checkbox is checked then the product view of this site's admin(s) will also be counted alongside other users.</p>
				</div>

				<br />
				<br />

				<div class="form-group">
					<label for="<?php echo $this->__('available-to-all');?>">Available To All : </label>
					<input type="checkbox" class="form-control" id="<?php echo $this->__('available-to-all');?>" name='<?php echo $this->__('available-to-all');?>' <?php if(get_option($this->__('available-to-all')) == 'on') echo 'checked';?> >
					<p class="help-block">If this checkbox is checked then the product view data will be shown to all the registered and unregistered visitors of this site.</p>
				</div>

				<br />
				<br />

				<button type="submit" class="btn btn-primary">Save Settings</button>

			</form>

		</div>
		<hr style='border-color:red;' />
		<div class='xs-col-12 sm-col-12 md-col-6 lg-col-6 text-center page-header'>
			<h5>Thanks for creating with <a href="https://wordpress.org/plugins/<?php echo $this->prefix;?>"><?php echo $this->name; ?></a>.</h5>
			<p>If you like our plugin please leave us a <a href="https://wordpress.org/support/view/plugin-reviews/<?php echo $this->prefix;?>">review</a>.</p>
		</div>

	</div>

</div>
