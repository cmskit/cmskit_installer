# cms-kit installer

**Notice: this is pre-production! Don't use it!**

This installation script is a web frontend and wrapper to [composer](https://getcomposer.org), a command line application to manage installations.

It is packed as a single-file application so you don't need any additional files.

* Download install.php and upload it to your web server
* Call install.php via web and coose te packages you want to install

## Alternatives

### Manually

There is no need to use an installer at all! 
Simply download the package you want, 
extract it 
and copy/upload it to the right location (mostly described in "readme.md" in the root folder of the package). 
If the package relies on other packages you need to download them as well. Dependencies are described in "composer.json" in the root folder of the package.
