#ThinFrame Applications

ThinFrame Applications is a PHP library build on top of Symfony2 Dependency Injection Container (S2DiC) that provides an abstract use over any PHP library/component.


Using the S2DiC, you can define what services your component provides, which extensions/compiler passes it uses and some other details. This way, when you want to use a specific component, you don't have to worry about configuring it the right way or other related things. Just instantiate that specific app and request the service you need.

Each app contains the following things:

* A container builder for S2DiC
* A application name
* A list of applications that are used
* Configuration files (standard S2DiC yml files)

Each app contains it's own `container builder`, but when they are chained, the top level app that you are using will merge all parent `container builders` into it's own. So, you will get a single `container builder` with all the services already configured.

##Features:

* Application specific dependency injection container
* Chained applications
* Advanced configuration for S2DiC
* Supports aware interfaces, so when you request a service, it will be automaticaly injected with the needed dependency.


##Installation:

* via Composer: `"thinframe/applications":"dev-master"`


##Copyright

* MIT License - Sorin Badea <sorin.badea91@gmail.com>