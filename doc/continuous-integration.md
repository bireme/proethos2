Continuous Integration
======================

![Build](https://codeship.com/projects/e61c3520-93d1-0134-acbe-426698f4d6ff/status?branch=master)

The automatic build and test of proethos2 is hosted on [Codeship](https://codeship.com/).

At the moment, the coverage tests are to branch master and any deployments are made.

Exceptions
----------

Because we can't install dependencies in codeship, at the build moment, we had to remove the test that envolves to create PDFs.

Codeship configurations
-----------------------

### Setup command
```
phpenv local 5.6
cd symphony/
mkdir -p uploads/documents
make setup_ci

```

### Test Commands
```
make test_ci

```
