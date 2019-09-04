## WP Plugin Skeleton

## Requirements

- [PHP](http://php.net/) v7.0+
- [Git](https://git-scm.com/)
- [Composer](https://getcomposer.org/) (globally installed as `composer`)

## Install

Execute this script below in your `wp-content/plugins` to generate a new plugin boilerplate:

```bash
git clone https://github.com/luizbills/wp-plugin-skeleton.git _skeleton \
&& cd $_ \
&& php bin/install.php && sleep .1 \
&& cd ../$(cat ../.tmp_wp_plugin_dir) \
&& rm -f ../.tmp_wp_plugin_dir \
&& rm -rf ../_skeleton \
&& ls -Apl
```

## Features

- Simple installation (see above)
- Several [helper functions](src/core/functions)
- Easy [static file management](src/core/classes/Utils/Asset_Manager.php)
- Powerful [template helpers](src/core/functions/template.php)

## Contributing

- For features or bug fixes, follow the [CONTRIBUTING guide](CONTRIBUTING.md).
- Or create an issue for suggestions and other reports.

## LICENSE

GPL v3
