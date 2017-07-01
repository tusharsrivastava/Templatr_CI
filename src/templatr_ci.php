<?php
/*
 * Filename : templatr_ci.php
 * Author : Tushar Srivastava (tusharsrivastava)
 *------------------------ DESCRIPTION ---------------------------------
 * "Templatr_CI" is a library for CodeIgniter which is used to 
 * generate a full page using pieces of blocks stored in a template
 * style i.e. header.php , footer.php, navbar.php etc....
 * You will have a file which is used as the content file which will be
 * different for each page but the header, footer and navbar etc... will
 * be a single file which is used by "Templatr_CI" to generate the final
 * view.
 * ---------------------------------------------------------------------
 * Dependencies: CodeIgniter - Obviously since it's a CI library ;-) 
 */

 /* Our Templatr_CI class */
 class Templatr_CI
 {
   	// Private Variable $CI is used to store the global super class instance
	private $CI;

	// Required Variables (These variables are set in either config file
        // can be passed directly in an array if required)

	// This variable "$template_dir" contains the directory name where the template is stored
	// the directory must be located in "application/views/" directory of CodeIgniter
	var $template_dir = '';

	// This variable "$header_file" contains the name of the header template file. It is php file.
	var $header_file = 'header.php';

	// This variable "$footer_file" contains the name of the footer template file. It is php file.
	var $footer_file = 'footer.php';

	// This variable "$sidebar_file" contains the name of the sidebar template file. If your webpage
	// does not have a sidebar, set this value to "0"
	var $sidebar_file = 'sidebar.php';

	// This variable "$nav_file" contains the name of the navbar template file. If you webpage does
	// not have a navbar (hightly unlikely), set this value to "0"
	var $nav_file = 'nav.php';

	// Important: (Setting the following "$view_template" variable will yield nothing)
	// It is used to render the current view which is passed in the method ("view")
	var $view_template = '';

	// The way "Templatr_CI" work is, it gets a list of filenames and then 
        // arrange them in a order that you can specify. This makes the "Templatr_CI"
        // a very powerful Templating library.
	var $priority = array('header','nav','sidebar','content','footer'); 

	// The Constructor of the library class
	public function __construct()
	{
		// Gets the CI superclass instance
		$this->CI =& get_instance();

		// Get the parameters from the config file, the "$config['templatr_config']"
		// is used to pass the array if you wish to add the configs to the config file.
		$param = $this->CI->config->item('templatr_config');

		// If it exists, the parameters will be initialized (pretty simple, right?)
		if($param && count($param) > 0)
		{
			$this->initialize($param);
		}
	}

	// This is the public 'initialize' method, if you choose not to set the parameters in
	// a config file, you can use this function to intialize all the parameters.
	public function initialize($param = array())
	{
		// If the parameter passed is not empty, the loop will set/override the parameters
		if(count($param) > 0)
		{
			// Loop each $key=>$value pair
			foreach($param as $key=>$val)
			{
				// If the variable named ($key)'s value exists in the class's data
				// member, the value is added to that member
				if(isset($this->$key))
				{
					$this->$key = $val;
				}
			}
		}
		// Fix the Trailing Slash Issue
		$this->template_dir = rtrim($this->template_dir,'/') . '/';
	}

	// An interesting hack here ;-) , if you have all the file's name same and you have multiple templates
	// just use this method to quickly change the template's directory. (How cool is that?)
	public function define_template_dir($dir)
	{
		// Add the directory's name
		$this->template_dir = rtrim($dir,'/') . '/';
	}

	// This is the main method you will be using almost... always ;-)
	// The prototype is quite similar as CI's view method ($this->load->view())
	// Parameter 1 is the name of the current name. Example: 'home','about' etc..
	// Parameter 2 is the array of the data parameter you generally pass with the 
	// CI's view method. Is exactly same.
	// Parameter 3 is new. If you donot want to use template, either for debug purposes
	// or if a particular pge might not require a template. Example: Successful form submission message
	// just set this parameter to false.
	public function view($view_template, $params=array(),$use_template=true)
	{
		// We are adding '.php' at the end of the filename after removing .php from the name, it is required
		// 'cause with this method we can be dead sure that the filename has only one '.php' extension either
		// it's passed in the '$view_template' or not (making it flexible) 
		$this->view_template = basename($view_template, '.php') . '.php';

		// If the $use_template is set (it's set by default)
		if($use_template)
		{
			// We are getting into a loop with a incrementing counter
			$next = 0;
			// We will get the template from an internal method called, get_priority()
			// pass in the current counter value and it returns the template file name
			$template = $this->get_priority($next);
			// Get inside the do-while loop
			do
			{
				// We will check if the file exits and also, if $template is not set to 0 (remember ?)
				// Ok, let me repeat. If the template name is set to 0 we are not using that block in the
				// template, so we can delibrately set a block name to 0 thus making this engine flexible ;-)
				if(file_exists(realpath(APPPATH . 'views/' . $this->template_dir . $template)) || $template=='0')
				{
					if ($template != '0')
					{
						$this->CI->load->view($this->template_dir . $template, $params);
					}
				} elseif($template=='0' || file_exists(realpath(APPPATH . 'views/' . $template))) {
					if ($template != '0')
					{
						$this->CI->load->view($template, $params);
					}
				} else 
				{
					// If the file not found (this can be thrown at any stage of the template, show 404
					show_404();
				}
				// Then increment the counter (that's why I use do-while)
				$next++;
				// and get the next template
				$template = $this->get_priority($next);
			}
			while($template != -1); // The get_priority method returns a -1 when the loop finishes
		}
		else
		{
			// Now, if we are not using the template (remember $use_template)

			// If file exists, loads it, else show 404. Simple :-P
			if(file_exists(realpath(APPPATH . 'views/' . $this->template_dir . $this->view_template)))
			{
				$this->CI->load->view($this->template_dir . $this->view_template, $params);
			}
			else
			{
				show_404();
			}
		}
	}

	// This is a private method. It is used to get the next template file.
	// It uses '$priority' data member to return the next template file.
	private function get_priority($next)
	{
		// We will first look if the index exists (I am using isset 'cause it's easy) ;-)
		if(isset($this->priority[$next]))
		{
			// I am using switch-case and it pretty well explains itself
			switch($this->priority[$next])
			{
				case 'header':
					if ($this->header_file != null && $this->header_file != '') {
						return basename($this->header_file, '.php') . '.php';
					}
					return 0;
					break;
				case 'nav':
					if ($this->nav_file != null && $this->nav_file != '') {
						return basename($this->nav_file, '.php') . '.php';
					}
					return 0;
					break;
				case 'sidebar':
					if ($this->sidebar_file != null && $this->sidebar_file != '') {
						return basename($this->sidebar_file, '.php') . '.php';
					}
					return 0;
					break;
				case 'content':
					return $this->view_template;
				case 'footer':
					if ($this->footer_file != null && $this->footer_file != '') {
						return basename($this->footer_file, '.php') . '.php';
					}
					return 0;
					break;
				default:
					return -1;
					break;
			}
		}
		// When the index does not exists, return -1
		return -1;
	}
 }

/* -- END OF FILE -- */
