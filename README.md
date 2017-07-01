Templatr_CI
==========
A Templating Library for CodeIgniter to use templates easily and fast with less coding.
Usage
-------
To use the library, copy the _Templatr_CI.php_ file to `application/libraries/` directory of CodeIgniter.
Now, to use the _Templatr_CI_ globally, I recommend using the following method.
### 1. Add the configs to `config.php` file.
```php
// Add the file names that you are using for the header, footer, sidebar and navbar
// templates. Optional: Add the extension .php
$templatr_config['header_file'] = 'my_header';
$templatr_config['footer_file'] = 'my_footer';
// You can set any of the entries to null or empty string if your application is not using it
$templatr_config['sidebar_file'] = null;
$templatr_config['nav_file'] = 'my_navbar';
// Setup the directory and priority order. The directory must be located at application/views/
$templatr_config['template_dir'] = 'template';
// Remember the names shown below must not be changed. Only order can be changed. Setting an 
// entry to be 0 omits the template section while processing. It is helpful when used in dynamic
// templating as shown later
$templatr_config['priority'] = array('header','nav',0,'content','footer'); 

// Now finally add this temporary array to main '$config['templatr_config']' array
$config['templatr_config'] = $templatr_config;
```
### 2. Now add the autoload entry in `autoload.php` file.
```php
$autoload['libraries'] = array('templatr_ci','database');
```
---
Remember though that the array contains more than one library and the above code is just an example.Also,both `autoload.php` and `config.php` files exists in `application/config/` directory.
### 3. Use it in a Controller
Let's see an example controller's index method
```php
class Example_Controller extends CI_Controller
{
    public function index()
    {
        $this->templatr_ci->view('home');
    }
}
```
Another example that uses some parameter to be passed in view as offred by CI base View class
```php
class Example_Controller extends CI_Controller
{
    public function index() 
    {
        $data_array = array('title'=>'My Title');
        $this->templatr_ci->view('home', $data_array);
    }
}
```
Another use case is, if we want to omit a section, say navbar, in a particular page view. We can dynamically set that as shown below
```php
class Example_Controller extends CI_Controller
{
    public function index()
    {
        // We have omitted navbar in this scenario
        $this->templatr_ci->set_priority(array('header', 'content', 'footer'));
        // Finally Render the View
        $this->templatr_ci->view('home');
    }
}
```
Suppose we want to change our navbar for some reason. Say to show different navbar to logged in users and guest users.
```php
class Example_Controller extends CI_Controller
{
    public function index()
    {
        // Use the initialize function to do so as follow
        $this->templatr_ci->initialize(array('nav_file'=>'navbar_logged_in'));
        // Finally Render the View
        $this->templatr_ci->view('home');
    }
}
```
Now, suppose that we have some page that does not follow our template structure for some reasons and thus we want it to be rendered without the template. This can be achieved as follow
```php
class Example_Controller extends CI_Controller
{
    public function index()
    {
        // The first parameter is view, second parameter is array and third 
        // parameter controls if we want to use template or not
        $this->templatr_ci->view('home', null, false);
    }
}
```
Finally, Suppose for some reason one of our section as completely different template. This is a generic use case where the admin panel has a different UI than rest of the website
```php
class Example_Admin_Controller extends CI_Controller
{
    public function __construct()
    {
        // Set the template different from rest of the website in this section
        $this->templatr_ci->define_template_dir('admin_panel');
    }

    public function index()
    {
        $this->templatr_ci->view('home');
    }
}
```
