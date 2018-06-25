<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('ACCOUNT', 'ixqwhufwhosting');
define('PASSSWORD', 'iN3TXthosjix');
define('DOMAIN', 'titaniumjsc.com');

class Subdomain extends CI_Controller {


	public function __construct()
	{
		parent::__construct();
		$this->load->model('Subdomain_model');
	}

	public function index()
	{	
		if($this->input->post())
		{
			$this->form_validation->set_rules('subdomain', 'Subdomain', 'min_length[3]|max_length[32]|is_unique[tblsubdomain.subdomain_name]');
			$this->form_validation->set_message('is_unique', "The Subdomain address has already been registered.");

			if ($this->form_validation->run() == TRUE)
			{
				$data = array(
					'subdomain_name' 	=> str_replace(" ","_",$this->input->post('subdomain')),
					'email' 			=> $this->input->post('email'),
					'password' 			=> password_hash($this->input->post("pwd"), PASSWORD_DEFAULT),
					'datecreated'       => date('Y-m-d H:i:s')
				);

				if($this->Subdomain_model->insert($data))
				{

					// /***********/ 
					// // Insert Subdomain
					// /***********/ 

					// //Set the name for your New Sub Domain
					$subDomain 	= $data['subdomain_name'];
					//cPanel Username
					$cPanelUser = ACCOUNT;
					//cPanel Password
					$cPanelPass = PASSSWORD;
					//Main Domain Name
					$rootDomain = DOMAIN;
					//Path Name
					$dirName 	= "public_html/". $data['subdomain_name'] . "." . DOMAIN;
					
					// Create new subdomain in cpanel	
					// $buildRequest = "frontend/paper_lantern/subdomain/doadddomain.html?domain=". $subDomain ."&rootdomain=". $rootDomain ."&dir=". $dirName ."";
					// //Open the socket
					// //$openSocket = fsockopen('localhost',2082); // http
					// $openSocket = fsockopen('ssl://nethost-1711.inet.vn',2083); // https
					// if(!$openSocket) {
					//     //SHow error
					//     return "Socket error";
					//     exit();
					// }

					// //Login Details
					// $authString = $cPanelUser . ":" . $cPanelPass;
					// //Encrypt the Login Details 
					// $authPass = base64_encode($authString);
					// //Request to Server using GET method
					// $buildHeaders  = "GET " . $buildRequest ."\r\n";
					// //HTTP
					// $buildHeaders .= "HTTP/1.0\r\n";
					// //Define Host
					// $buildHeaders .= "Host:". $rootDomain ."\r\n";
					// //Request Authorization
					// $buildHeaders .= "Authorization: Basic " . $authPass . "\r\n";
					// $buildHeaders .= "\r\n";

					// //fputs
					// fputs($openSocket, $buildHeaders);
					// while(!feof($openSocket))
					// {
					// 	fgets($openSocket,128);
					// }
					// fclose($openSocket);

					// // Create file index.php in folder new site.
					// $passToShell = "touch /home/". $cPanelUser ."/public_html/". $subDomain . "." . DOMAIN  . "/index.php";
					// $passToShell = "echo “Hello " . $subDomain . " ” >> /home/". $cPanelUser ."/public_html/". $subDomain . "." . DOMAIN  . "/index.php";
			 	// 	system($passToShell);
				

					/************/ 
					// //Install folder new site
					/************/ 			

			 		/* Server Link */
			 		// $path_get_newsite 	= $_SERVER["DOCUMENT_ROOT"]. '/CI/new_site/install/';
			 		// $path_newsite 		= $_SERVER["DOCUMENT_ROOT"]. '/CI/demo/' . $subDomain;
			 		
					/* Localhost path */
			 		$path_get_newsite 	= $_SERVER["DOCUMENT_ROOT"]. '/CI/new_site/install/';
			 		$path_newsite 		= $_SERVER["DOCUMENT_ROOT"]. '/CI/demo/' . $subDomain;
			 		$saved_file_location = $path_get_newsite . 'perfex_crm.zip';
			 		$saved_file_locations = $path_get_newsite . 'database.sql';
			 		if(!file_exists($path_newsite))
			 		{
						mkdir($path_newsite);
			 		}		
					
					// check folder empty
					$return_folder = (count(glob($path_newsite . "/*")) === 0) ? 'Empty' : 'Not empty';
					if($return_folder == "Empty")
					{	
						// extract file .zip
						$zip = new ZipArchive;
						if ($zip->open($saved_file_location) === TRUE)
						{
						    $zip->extractTo($path_newsite . '/');
						    $zip->close();
						    $success_folder = true;
						}						

						// Import Database
						if($success_folder == true)
						{

							//ENTER THE RELEVANT INFO BELOW
							//$urlHostName 			=	$subDomain .".". $_SERVER['HTTP_HOST'];
							$urlHostName 			=	"http://localhost/CI/demo/" . $subDomain;
							$mysqlHostName 			=	'localhost';
							$mysqlUserName 			=	'root';
							$mysqlPassword 			=	'';
							$mysqlDatabaseName 		= 	'ci_'. $subDomain;
							$mysqlImportFilename 	= 	$saved_file_locations;

							// Write file app-config.php
							$path_newsitess = $_SERVER["DOCUMENT_ROOT"]. '/CI/demo/'. $subDomain .'/application/config/app-config.php';
							$myfile =  fopen($path_newsitess, "w") or die("Unable to open file!");
							$txt = "<?php \n";
							$txt .= "defined('BASEPATH') OR exit('No direct script access allowed');\n";
							$txt .= "define('APP_BASE_URL','$urlHostName');\n";
							$txt .= "define('APP_DB_HOSTNAME','$mysqlHostName ');\n";
							$txt .= "define('APP_DB_USERNAME','$mysqlUserName');\n";
							$txt .= "define('APP_DB_PASSWORD','$mysqlPassword');\n";
							$txt .= "define('APP_DB_NAME','$mysqlDatabaseName');\n";
							fwrite($myfile, $txt);
							fclose($myfile);							

							$conn = new mysqli($mysqlHostName, $mysqlUserName, $mysqlPassword);

							if ($conn->connect_error)
							{
							    die("Connection failed: " . $conn->connect_error);
							}

							// Create Database  new site.
							$sql = "CREATE DATABASE $mysqlDatabaseName";

							if ($conn->query($sql) === TRUE)
							{
								// Connect new site database
								$mysqli = new mysqli($mysqlHostName, $mysqlUserName, $mysqlPassword, $mysqlDatabaseName);

								// import database file.sql
								$sql = file_get_contents($mysqlImportFilename);

								 /* check connection */
								if (mysqli_connect_errno())
								{
								    printf("Connect failed: %s\n", mysqli_connect_error());
								    exit();
								}

								/* execute multi query */
								if($mysqli->multi_query($sql))
								{
									// Strict Standards: mysqli_next_result() error with mysqli_multi_query
									while ($mysqli->more_results() && $mysqli->next_result()) {;}

									// Insert user database 
									$datecreated 	= date('Y-m-d H:i:s');
									$email 			= $data['email'];
									$password 		= $data['password'];
									$sql = "INSERT INTO tblstaff (firstname, lastname, password, email, datecreated, admin, active) VALUES('abc', 'def', 'password', 'email', 'datecreated', 1, 1)";
							        if($mysqli->query($sql) == TRUE)
							        {
							        	echo 'Success!';
							        }
							        else
							        {
							        	echo 'Error!';
							        }

							        $mysqli->close();
								}

								$conn->close();
							}
							else
							{
							    echo "Error creating database: " . $conn->error;
							}
						}
					}
				}

				redirect(base_url(''));
			}
		}	

		$data['title'] = ('Subdomain');
		$this->load->view('subdomain/index', $data);
	}

	public function view()
	{
		$data['data_result'] = $this->Subdomain_model->getAll();
		$data['title'] = ('View Subdomain');
		$this->load->view('subdomain/view', $data);		
	}

	public function delete()
	{

		if($this->input->post())
		{	
			$subdomain = $this->input->post('subdomain');
			
			if($this->Subdomain_model->delete($subdomain))
			{
	
					// /***********/ 
					// // Delete Subdomain
					// /***********/ 

					//Set the name for your New Sub Domain
					$subDomain 	= $subdomain;
					//$subDomain 	= 'domain01';
					//cPanel Username
					$cPanelUser = ACCOUNT;
					//cPanel Password
					$cPanelPass = PASSSWORD;
					//Main Domain Name
					$rootDomain = DOMAIN;
					//Path Name

					// //$buildRequest = "frontend/paper_lantern/subdomain/dodeldomain.html?domain=". $subDomain ."_". $rootDomain;
					// $buildRequest = "frontend/paper_lantern/subdomain/dodeldomain.html?domain=". $subDomain ."_". $rootDomain;
					// //Open the socket
					// //$openSocket = fsockopen('localhost',2082); // http
					// $openSocket = fsockopen('ssl://nethost-1711.inet.vn',2083); // https
					// if(!$openSocket) {
					//     //SHow error
					//     return "Socket error";
					//     exit();
					// }

					// //Login Details
					// $authString = $cPanelUser . ":" . $cPanelPass;
					// //Encrypt the Login Details 
					// $authPass = base64_encode($authString);
					// //Request to Server using GET method
					// $buildHeaders  = "GET " . $buildRequest ."\r\n";
					// //HTTP
					// $buildHeaders .= "HTTP/1.0\r\n";
					// //Define Host
					// $buildHeaders .= "Host:". $rootDomain ."\r\n";
					// //Request Authorization
					// $buildHeaders .= "Authorization: Basic " . $authPass . "\r\n";
					// $buildHeaders .= "\r\n";

					// //fputs
					// fputs($openSocket, $buildHeaders);
					// while(!feof($openSocket))
					// {
					// 	fgets($openSocket,128);
					// }
					// fclose($openSocket);

					// // Delete server folder new site
					// $passToShell = "rm –R /home/". $cPanelUser ."/public_html/". $subDomain . "." . DOMAIN;
			 		//exec($passToShell);
					$passToShell = "rm -rf /home/". $cPanelUser ."/public_html/". $subDomain . "." . DOMAIN;
			 		system($passToShell);

			 		// Delete localhost folder new site
			 		$path_newsite = $_SERVER["DOCUMENT_ROOT"]. '/CI/demo/'. $subDomain;
			 		$this->delete_dir($path_newsite);
			 		
					//ENTER THE RELEVANT INFO BELOW
					$mysqlHostName 			=	'localhost';
					$mysqlUserName 			=	'root';
					$mysqlPassword 			=	'';
					$mysqlDatabaseName 		= 	'ci_'. $subDomain;

					$conn = new mysqli($mysqlHostName, $mysqlUserName, $mysqlPassword);

					if ($conn->connect_error)
					{
					    die("Connection failed: " . $conn->connect_error);
					}

					$sql = "DROP DATABASE ". $mysqlDatabaseName ."";

					if ($conn->query($sql) == TRUE)
					{
					    echo "Database ci_". $subDomain ." was successfully dropped";
					}

					$conn->close();
			}
		}
	}

	/*******************/ 
	// function Delete folder
	/*******************/
	public function delete_dir($file)
	{ 
	    if (file_exists($file)) { 
	        chmod($file, 0777); 
	        if (is_dir($file)) { 
	            $handle = opendir($file);  

	            while($filename = readdir($handle)) { 
	                if ($filename != "." && $filename != "..") { 
	                    $this->delete_dir($file."/".$filename); 
	                } 
	            } 
	            closedir($handle); 
	            rmdir($file); 
	        } else { 
	            unlink($file); 
	        } 
	    } 
	}

	/*******************/ 
	// function copyfile
	/*******************/ 
	public function copy_directory( $source, $destination ) {
        if ( is_dir( $source ) ) {
        @mkdir( $destination );
        $directory = dir( $source );
        while ( FALSE !== ( $readdirectory = $directory->read() ) ) {
            if ( $readdirectory == '.' || $readdirectory == '..' ) {
                continue;
            }
            $PathDir = $source . '/' . $readdirectory; 
            if ( is_dir( $PathDir ) ) {
                $this->copy_directory( $PathDir, $destination . '/' . $readdirectory );
                continue;
            }
            copy( $PathDir, $destination . '/' . $readdirectory );
        }

        $directory->close();
        }else {
        copy( $source, $destination );
        }
    }

	/*******************/ 
	// Get URL new site
	/*******************/  
    public function guess_base_url()
    {
        $base_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ? 'https' : 'http';
        $base_url .= '://'. $_SERVER['HTTP_HOST'];
        $base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
        $base_url = preg_replace('/install.*/', '', $base_url);

        return $base_url;
    }

}

/* End of file Subdomain.php */
/* Location: ./application/controllers/subdomain.php */