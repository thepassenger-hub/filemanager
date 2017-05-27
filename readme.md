# FileManager

Personal project made with Laravel + Vue.js. Very simple file manager that performs basic crud operations.


![screenshot of file creation](/public/images/create.png?raw=true "Create File")

![screenshot of file upload](/public/images/avatar-save.png?raw=true "Upload File")

### Features

* Navigate between folder by double clicking the folders or by using the breadcrumb navigation bar.
* Upload a file
* Create a empty file or a directory.
* Move a file or a directory.
* Copy a file or a directory.
* Rename a file or a directory.
* Chmod of a file or a directory.
* Delete a file or a directory. Be careful when using this action. It's not reversible.

### Installing

Not planning to release a package since there are better versions out there.

If you wanna try it on your local machine feel free to do so.

#### Warning: Be caureful when deleting files. You will not be able to retrieve them.

```
git clone git@bitbucket.org:thepassenger/filemanager.git
composer install
npm run dev
```

And visit your localhost

## Deployment

You can deploy it on your server but be careful to restric access on routes.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
