<div class="users form">
<?php echo $this->Form->create('User');?>
	<fieldset>
		<legend><?php __('Add User'); ?></legend>
	<?php
		echo $this->Form->input('usercode');
		echo $this->Form->input('email');
		echo $this->Form->input('password');
		echo $this->Form->input('fullname');
		echo $this->Form->input('company');
		echo $this->Form->input('phone');
		echo $this->Form->input('local_phone');
		echo $this->Form->input('role');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Users', true), array('action' => 'index'));?></li>
	</ul>
</div>