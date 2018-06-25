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
					<table class="table table-hover">
					    <thead>
					      <tr>
					      	<th>Id</th>
					        <th>SubDomain Name</th>
					        <th>Email</th>
					        <th>Date Created</th>
					        <th>Status</th>
					      </tr>
					    </thead>
					    <tbody>
							<?php
								if(isset($data_result)) : 
									foreach ($data_result as $value) :
							?>
					      	<tr>
						        <td>
						        	<span><?= $value->id; ?></span>
						        </td>
						        <td>
						        	<span><?= $value->subdomain_name; ?></span>
						        </td>
						        <td>
						        	<span><?= $value->email; ?></span>
						        </td>
						        <td>
						        	<span><?= $value->datecreated; ?></span>
						        </td>
						        <td>
						        	<span class="btn btn-default btn-delete-subdomain" data-delete-subdomain="<?= $value->subdomain_name; ?>">Delete</span>
						        </td>
					      	</tr>
					      	<?php
					      			endforeach;
					      		endif;
					      	?>
					    </tbody>
					</table>	   				
	   			</div>
	   		</div>
	   </div>
	</div>
	<script src="<?= base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
	<script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.min.js'); ?>"></script>
	<script src="<?= base_url('assets/js/custom.js'); ?>"></script>
	<script>
		$('.btn-delete-subdomain').on('click', function(){
			$subdomain = $(this).attr('data-delete-subdomain');
			//var token_name = '<?=  $this->security->get_csrf_token_name() ?>';
			//var token_value = '<?= $this->security->get_csrf_hash(); ?>';
			$.ajax({
				url: 'http://localhost/CI/new_site/subdomain/delete',
				type: 'POST',
				data : {
					subdomain : $subdomain,
				},
				success : function()
				{
					location.reload();
				},
				error : function()
				{
					alert('Error');
				}
			});
		});		
	</script>
</body>
</html>