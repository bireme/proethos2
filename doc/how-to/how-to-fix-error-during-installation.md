How to fix error during installation in PHP 7.2+
================================================

In PHP 7.2+, occurs the following error during installation:

```Warning: "continue" targeting switch is equivalent to "break". Did you mean to use "continue 2"?```

This is due to an incompatibility between Doctrine ORM libraries and PHP 7.2+

To fix it, just run the next commands:

```
$ cd ~/project/proethos2/git/tools
$ ./fix-doctrine-orm.sh
```