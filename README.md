patch-installer
===============

Patch other composer packages on install or update.

**experimental feature**

Usage
-----

For a `patch` [type](http://getcomposer.org/doc/04-schema.md#type) change the install path to vendor directory and merge into other vendor directories.

Your composer.json

```json
{
    "type": "patch",
    "require": {
        "some/dependency": "*",
        "goatherd/patch-installer": "*"
    },
    "extra": {
        "patch-path": "some/dependency",
        "patch-files": [
            "path/to/firstFile.ext",
            "config.xml"
        ]
    }
}
```

Limitations
-----------

* order of installation is important
* do not try to patch a patch
* can not uninstall (but updates)
