
<?php echo validation_errors(); ?>
<?php echo form_open('Account/changePassword'); ?>

<label for="login"> Login :</label>
<input type="text" name="login" value="<?php echo $this->input->post('login'); ?>" id="login"/></br>

Saisissez votre nouveau mot de passe : </br></br>

<label for="password">Nouveau mot de passe : </label>
<input type="password" name="password" value="<?php echo $this->input->post('password'); ?>" id="password"/></br>

<label for="passwordConfirmation">Confirmez votre nouveau mot de passe : </label>
<input type="password" name="passwordConfirmation" value="<?php echo $this->input->post('passwordConfirmation'); ?>" id="passwordConfirmation"/></br>

<button type="submit"> valider</button>
<?php echo form_close(); ?>

