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
// templates. Must add the extension .php
$templatr_config['header_file'] = 'my_header.php';
$templatr_config['footer_file'] = 'my_footer.php';
$templatr_config['sidebar_file'] = 'my_sidebar.php';
$templatr_config['nav_file'] = 'my_navbar.php';
// Setup the directory and priority order. The directory must be located at application/views/
$templatr_config['template_dir'] = 'template';
// Remember the names shown below must not be changed. Only order can be changed.
$templatr_config['priority'] = array('header','nav','sidebar','content','footer'); 

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
