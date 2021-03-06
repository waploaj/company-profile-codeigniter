<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('ion_auth','form_validation','pagination'));
		$this->load->model('product_model');		
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));			
		$this->data['csrf'] = array(
	        'name' => $this->security->get_csrf_token_name(),
	        'hash' => $this->security->get_csrf_hash()
			);
	}

	public function index(){
		if($this->ion_auth->logged_in()){		

			$config = array();
      $config["base_url"] = base_url() . "products/index/";
      $config["total_rows"] = $this->product_model->record_count();
      $config["per_page"] = 3;
      $config["uri_segment"] = 3;

      // bootstrap styling
      $config['full_tag_open'] = "<ul class='pagination pagination-sm' style='position:relative; top:-25px;'>";
	     $config['full_tag_close'] ="</ul>";
	     $config['num_tag_open'] = '<li>';
	     $config['num_tag_close'] = '</li>';
	     $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
	     $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
	     $config['next_tag_open'] = "<li>";
	     $config['next_tagl_close'] = "</li>";
	     $config['prev_tag_open'] = "<li>";
	     $config['prev_tagl_close'] = "</li>";
	     $config['first_tag_open'] = "<li>";
	     $config['first_tagl_close'] = "</li>";
	     $config['last_tag_open'] = "<li>";
	     $config['last_tagl_close'] = "</li>";

      $this->pagination->initialize($config);

      $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
      $this->data["view"] = $this->product_model->fetch_products($config["per_page"], $page);
      $this->data["links"] = $this->pagination->create_links();

			$this->data['num'] = $this->uri->segment('3') + 1;

			$this->load->view('layouts/backend/header');
			$this->load->view('products/index',$this->data);
			$this->load->view('layouts/backend/footer');
		}else{
			redirect('/','refresh');
		}
	}

	public function add(){
		if($this->ion_auth->logged_in()){
			$this->data['category'] = $this->product_model->get_category();
			$this->load->view('layouts/backend/header');
			$this->load->view('products/create',$this->data);
			$this->load->view('layouts/backend/footer');
		}else{
			redirect('/','refresh');
		}
	}

	public function create(){
		if($this->ion_auth->logged_in()){
			$user = $this->ion_auth->user()->row();
			$this->form_validation->set_rules('title', 'Title', 'required');
			$this->form_validation->set_rules('link', 'Link', 'valid_url');
			$this->form_validation->set_rules('desc', 'Description', 'required');
			$this->form_validation->set_rules('userfile', 'Image', 'file_required|file_allowed_type[image]|file_image_mindim[100,100]');
			$this->form_validation->set_rules('file', 'File', 'file_allowed_type[pdf]');
			

			 if ($this->form_validation->run() == FALSE){
			 		$this->data['category'] = $this->product_model->get_category();
		    	$this->load->view('layouts/backend/header');
					$this->load->view('products/create',$this->data);
					$this->load->view('layouts/backend/footer');
		    }
		    else{	
	    		if($_FILES['userfile']['name']){
	    	     	$this->load->library('upload');
	    	     	$config['upload_path'] = './assets/images/products/';	    	     	
	    	     	$config['allowed_types']        = 'gif|jpg|png';      	    	     	
	    	     	$config['remove_spaces']        = TRUE;
	    	     	$config['overwrite']         		= TRUE;
	    	     	$this->upload->initialize($config);
	    	     	if($this->upload->do_upload('userfile')){		     		     		
	    	     			$info = $this->upload->data();
	    	     			if($_FILES['file']['name']){
	    	     				$this->load->library('upload');
	    	     				$config['upload_path'] = './assets/files/';	    	     	
	    	     				$config['allowed_types']        = 'pdf';      	    	     	
	    	     				$config['remove_spaces']        = TRUE;
	    	     				$config['overwrite']         		= TRUE;
	    	     				$this->upload->initialize($config);
	    	     				if($this->upload->do_upload('file')){
	    	     					$finfo = $this->upload->data();
	    	     				}
	    	     			}
		    		    	$data = array(
		    						'user_id' => $user->id,
		    						'title' => $this->input->post('title'),
		    						'link' => $this->input->post('link'),
		    						'category_id' => $this->input->post('category'),
		    						'description' => $this->input->post('desc'),
		    						'image' => $info['file_name'],
		    						'file_link' => $finfo['file_name'],
		    						'slug' => url_title($this->input->post('title')),
		    						'created_at' => date('Y-m-d H:i:s')
		    					 );

				    		$q = $this->product_model->insert($data);	
				    		if($q){
				    			$this->session->set_flashdata('message', 'insert data success');
				    			redirect('products');			    			
				    		}else{
				    			$this->session->set_flashdata('message', 'oops something is wrong');
				    			redirect('products');			    			
				    		}
				    		
	    	    	}	else{    	    		    	    		
	    	    		$this->session->set_flashdata('message', $this->upload->display_errors());
	    	    	}
	    		}
		    }		
			}else{
				redirect('/','refresh');
			}
		}


	public function edit($slug){
		if($this->ion_auth->logged_in()){			
			$this->data['show'] = $this->product_model->edit($slug);
			$this->data['category'] = $this->product_model->get_category();
			$this->load->view('layouts/backend/header');
			$this->load->view('products/edit',$this->data);
			$this->load->view('layouts/backend/footer');
		}else{
			redirect('/','refresh');
		}
	}


	public function update(){
		if($this->ion_auth->logged_in()){
				$user = $this->ion_auth->user()->row();
				$this->form_validation->set_rules('title', 'Title', 'required');
				$this->form_validation->set_rules('link', 'Link', 'valid_url');
				$this->form_validation->set_rules('desc', 'Description', 'required');
				$this->form_validation->set_rules('userfile', 'Image', 'file_allowed_type[image]|file_image_mindim[100,100]');
				$this->form_validation->set_rules('file', 'File', 'file_allowed_type[pdf]');
				
				$slug = $this->input->post('slug');

				 if ($this->form_validation->run() == FALSE){
				 		$this->data['show'] = $this->product_model->edit($slug);
				 		$this->data['category'] = $this->product_model->get_category();
			    	$this->load->view('layouts/backend/header');
						$this->load->view('products/edit',$this->data);
						$this->load->view('layouts/backend/footer');
			    }
			    else{	
			    	
				    	$this->load->library('upload');
				    	if(!empty($_FILES['userfile']['name'])&&!empty($_FILES['file']['name'])){				 
				    		$imgexist = './assets/images/products'.$this->input->post('img');
				    		$filexist = './assets/files/'.$this->input->post('filenm');

				    		unlink($imgexist);
				    		unlink($filexist);

				    		$config['upload_path'] = './assets/images/products/';	    	     	
				    		$config['allowed_types']        = 'gif|jpg|png';      	    	     	
				    		$config['remove_spaces']        = TRUE;
				    		$config['overwrite']         		= TRUE;
				    		$this->upload->initialize($config);			    		
				    		$this->upload->do_upload('userfile');
				    		$imginfo = $this->upload->data();


				    		$config['upload_path'] = './assets/files/';	    	     	
				    		$config['allowed_types']        = 'pdf';      	    	     	
				    		$config['remove_spaces']        = TRUE;
				    		$config['overwrite']         		= TRUE;
				    		$this->upload->initialize($config);
				    		$this->upload->do_upload('file');
				    		$fileinfo = $this->upload->data();

	    		    	$data = array(
	    						'user_id' => $user->id,
	    						'title' => $this->input->post('title'),
	    						'link' => $this->input->post('link'),
	    						'category_id' => $this->input->post('category'),
	    						'description' => $this->input->post('desc'),
	    						'image' => $imginfo['file_name'],
	    						'file_link' => $fileinfo['file_name'],
	    						'slug' => url_title($this->input->post('title')),
	    						'updated_at' => date('Y-m-d H:i:s')
	    					 );

				    	}elseif(!empty($_FILES['userfile']['name'])){				
				    		$imgexist = './assets/images/products'.$this->input->post('img');
				    		
				    		unlink($imgexist);
				    		
				    		$config['upload_path'] = './assets/images/products/';	    	     	
				    		$config['allowed_types']        = 'gif|jpg|png';      	    	     	
				    		$config['remove_spaces']        = TRUE;
				    		$config['overwrite']         		= TRUE;
				    		$this->upload->initialize($config);			    		
				    		$this->upload->do_upload('userfile');
				    		$imginfo = $this->upload->data();
	    		    	$data = array(
	    						'user_id' => $user->id,
	    						'title' => $this->input->post('title'),
	    						'link' => $this->input->post('link'),
	    						'category_id' => $this->input->post('category'),
	    						'description' => $this->input->post('desc'),
	    						'image' => $imginfo['file_name'],	    						
	    						'slug' => url_title($this->input->post('title')),
	    						'updated_at' => date('Y-m-d H:i:s')
	    					 );
				    	}elseif(!empty($_FILES['file']['name'])){			
						    	$filexist = './assets/files/'.$this->input->post('filenm');						    	
						    	unlink($filexist);	    		

					    		$config['upload_path'] = './assets/files/';	    	     	
					    		$config['allowed_types']        = 'gif|jpg|png';      	    	     	
					    		$config['remove_spaces']        = TRUE;
					    		$config['overwrite']         		= TRUE;
					    		$this->upload->initialize($config);			    		
					    		$this->upload->do_upload('file');
					    		$fileinfo = $this->upload->data();
		    		    	$data = array(
		    						'user_id' => $user->id,
		    						'title' => $this->input->post('title'),
		    						'link' => $this->input->post('link'),
		    						'category_id' => $this->input->post('category'),
		    						'description' => $this->input->post('desc'),
		    						'file_link' => $fileinfo['file_name'],	    						
		    						'slug' => url_title($this->input->post('title')),
		    						'updated_at' => date('Y-m-d H:i:s')
		    					 );
				    	}else{				    		
	    		    	$data = array(
	    						'user_id' => $user->id,
	    						'title' => $this->input->post('title'),
	    						'link' => $this->input->post('link'),
	    						'category_id' => $this->input->post('category'),
	    						'description' => $this->input->post('desc'),	    						    						
	    						'slug' => url_title($this->input->post('title')),
	    						'updated_at' => date('Y-m-d H:i:s')
	    					 );
				    	}
				    	$q = $this->product_model->update($data,$slug);
				    	if($q){
				    		$this->session->set_flashdata('message', 'update product success');
				    		redirect('products');			    			
				    	}else{
				    		$this->session->set_flashdata('message', 'oops something is wrong');
				    		redirect('products');			    			
				    	}

			    	}
			    
		}else{
			redirect('/','refresh');
		}
	}

	public function destroy($slug){
		$path = $this->product_model->edit($slug);
		foreach ($path as $pt){
			$path_image = './assets/images/products/'.$pt->image;
			$path_file = './assets/files/'.$pt->file_link;
		}
		
		$res = $this->product_model->destroy($slug);
		if($res){			
			unlink($path_image);
			unlink($path_file);
			$this->session->set_flashdata('message', 'deleted successfully');
			redirect('products');
		}else{
			$this->session->set_flashdata('message', 'oops something is wrong');
			redirect('products');			    			
		}
	}




	
}
