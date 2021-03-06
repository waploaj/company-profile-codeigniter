<!DOCTYPE html>
<html>
  <head>
    <title>DK-Internusa:Backend</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-wysiwyg/0.3.3/bootstrap3-wysihtml5.min.css">
    <!-- styles -->
    <link href="<?php echo base_url()?>assets/css/backend.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
  	<div class="header">
	     <div class="container">
	    <div class="container example5">
	      <nav class="navbar navbar-inverse">
	        <div class="container-fluid">
	          <div class="navbar-header">
	            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar5">
	              <span class="sr-only">Toggle navigation</span>
	              <span class="icon-bar"></span>
	              <span class="icon-bar"></span>
	              <span class="icon-bar"></span>
	            </button>
	            <a class="navbar-brand" href="http://www.dk-internusa.co.id"><img style="	width: 64px;" src="<?php echo base_url()?>assets/images/pp.jpeg" alt="DK-Internusa">Data Komunikasi Internusa
	            </a>
	          </div>
	          <div id="navbar5" class="navbar-collapse collapse">
	            <ul class="nav navbar-nav navbar-right">	             	               
	              <li><a href="<?php echo base_url().'auth/logout'?>"><i class="fa fa-power-off"> </i> logout</a></li>
	            </ul>
	          </div>
	          <!--/.nav-collapse -->
	        </div>
	        <!--/.container-fluid -->
	      </nav>

	    </div>   
			</div>
			<?php $this->load->view('layouts/backend/sidebar'); ?>
