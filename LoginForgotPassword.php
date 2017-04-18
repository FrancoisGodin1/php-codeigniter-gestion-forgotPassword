<?php echo validation_errors(); ?>
<?php echo form_open('Login/forgotPassword'); ?>

veuillez saisir votre login(email) : </br>

<label for="login"> Login :</label>
<input type="text" name="login" value="<?php echo $this->input->post('login'); ?>" id="login"/></br>
<button type="submit"> valider</button>
<?php echo form_close(); ?>

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

