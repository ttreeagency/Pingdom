Ttree.Neos.Pingdom
==================

Introduction
------------

Neos CMS package to display Pingdom report in the Neos CMS backend.

**Early version of this package, we be released on stable version with Neos 2.1**

Features
--------

![Backend Module](https://dl.dropboxusercontent.com/s/s44pt9rcy7uxuc2/2015-11-26%20at%2022.39%202x.png?dl=0)

- [ ] General Dashboard
- [x] List all Checks
  - [x] Filter the list of visible Checks
- [x] Show details about the Check (basic)
- [ ] Show advanced details about the Check (dashboard)
- [x] Pause / Unpause Check (with custom Privilege Target, by default only Administrator can use this feature)
- [ ] Create Check
- [ ] Edit Check
- [ ] Remove Check


Settings
--------

- ```username```: Your Pingdom username
- ```password```: Your Pingdom password
- ```token```: Your Pingdom Application Token
- ```checks.filter```: An array to limit the check visibility (only declared ID are visible in the module

Acknowledgments
---------------

Development sponsored by [ttree ltd - neos solution provider](http://ttree.ch).

We try our best to craft this package with a lots of love, we are open to sponsoring, support request, ... just contact us.

License
-------

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.

[PSR-2]: http://www.php-fig.org/psr/psr-2/
[PSR-4]: http://www.php-fig.org/psr/psr-4/
