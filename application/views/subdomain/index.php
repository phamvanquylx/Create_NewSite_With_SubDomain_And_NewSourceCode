<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1" />
    <link href="" rel="shortcut icon">
    <title></title>
    <link href='<?php echo base_url('assets/plugins/bootstrap/css/bootstrap.min.css'); ?>' rel='stylesheet' />
    <link href="<?php echo base_url('assets/css/custom.css'); ?>" rel="stylesheet">
</head>
<body>
	<div id="wrapper">
	   <div class="main-content-subdomain">
	   		<div class="container">
	   			<div class="row">
	   				<div class="col-xs-12 col-sm-8 col-sm-offset-2">
	   					<h1><?= $title; ?></h1>
	   					<span><a href="<?= base_url('subdomain/view'); ?>" class="btn btn-default">Listing SubDomain</a></span>
	   					<?php echo form_open($this->uri->uri_string(),array('id'=>'form_subdomain')); ?>
                            <?php if(validation_errors() !== '') : ?>
                                <div class="s-alert s-alert--danger">
                                    <div class="s-alert__text">
                                        <h3>Error</h3>
                                        <span><?php echo validation_errors(); ?></span>
                                    </div>
                                </div>
                            <?php endif;  ?>	   					
							<div class="form-group">
								<label for="subdomain">Hostname:</label>
								<input type="text" class="form-control" id="subdomain" placeholder="mywebsite" name="subdomain" required>
								<em>Example</em>
								<em>www.my-website.com</em>
								<em>subdomain.web-site.com</em>
							</div>							
							<div class="form-group">
								<label for="email">Email:</label>
								<input type="email" class="form-control" id="email" placeholder="Email name..." name="email" required>
							</div>
							<div class="form-group">
								<label for="pwd">Password:</label>
								<input type="password" class="form-control" id="pwd" placeholder="Enter password" name="pwd" required>
							</div>
							<button type="submit" class="btn btn-primary subdomain-submit">Submit</button>  						
							<button type="reset" class="btn btn-default">Reset</button>
						<?php echo form_close(); ?>	
	   				</div>
	   			</div>
	   		</div>
	   </div>
	</div>
	<script src="<?= base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
	<script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.min.js'); ?>"></script>
	<!-- <script src="<?= base_url('assets/js/custom.js'); ?>"></script> -->
</body>
</html>