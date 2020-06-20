## WP Plugin Skeleton

## Requirements

- [PHP](http://php.net/) v7.1+
- [Git](https://git-scm.com/)
- [Composer](https://getcomposer.org/) (globally installed as `composer`)

## Install

Execute this script below in your `wp-content/plugins` to generate a new plugin boilerplate:

```bash
git clone \
--branch master \
--single-branch --no-tags \
https://github.com/luizbills/wp-plugin-skeleton.git _skeleton \
&& cd _skeleton \ && php bin/install.php && sleep .1 \
&& cd ../$(cat ../.tmp_wp_plugin_dir) \
&& rm -f ../.tmp_wp_plugin_dir && rm -rf ../_skeleton \
&& ls -Apl
```

## Features

- Simple installation (see above)
- Several [helper functions](src/core/functions)
- Easy [JS & CSS management](src/core/classes/Utils/Asset_Manager.php)
- [Template helpers](src/core/functions/template.php) and [V](https://github.com/luizbills/v) integration.

## Contributing

- For features or bug fixes, follow the [CONTRIBUTING guide](CONTRIBUTING.md).
- Or create an issue for suggestions and other reports.

## LICENSE

GPL v3
