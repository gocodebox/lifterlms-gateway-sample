LifterLMS Sample Payment Gateway Plugin
=======================================

This plugin is an example of a working LifterLMS Payment Gateway integration.

The source of this plugin can be used to learn how a LifterLMS payment gateway is intended to work.


## How to use this plugin

This is a WordPress plugin which can be cloned or downloaded and installed as a WordPress plugin. It requires LifterLMS to function.

+ Visit the gateway's settings at LifterLMS -> Checkout -> Sample Gateway to enable and configure it
+ Add `SECRET` for the TEST and LIVE API Key options to make checkout "work".
+ Use any other API key (or leave it blank) to see the gateway error.
+ For success at checkout use credit card number `4242424242424242` with an expiration date that *looks like a real date* (eg `12/3232`) and any CVC number.
+ To see an example of custom validation errors, create an access plan that's less that `0.50` or greater that `1000.00`.
+ To see an example of card data validation errors use an expiration date that doesn't look like a real expiration date (eg `1234`).
+ To see an example of a declined card error use any card numbers other than the success card noted above.
+ Recurring payments will succeed 50% of the time.
+ Refunds can be processed via the gateway. They will never fail.


## How to build your own payment gateway

You can fork this repository as a starting point or simply read through the source to help learn how LifterLMS payment gateways *should* work.

A real gateway will be *much more complicated* than this example. Our goal is not to teach how to work with any possible gateway but how to work with LifterLMS. It is very general and you will be responsible for filling in the gaps between what this example illustrates and the real world requirements of the payment provider your gateway chooses to integrate with.


## Fake Payment Gateway API

Included in this plugin is a fake payment gateway API. The API is included by the plugin and can be found in the [fake-rest-api directory](./fake-rest-api).

This API is "functional" to show how the plugin *could* interact with a payment gateway API. It doesn't record or persist any data it retrieves and always
returns random IDs and a limited set of information.

It requires an API key, passed in API request headers

Use `SECRET` for successful authentication.

Most real APIs would have a more robust authentication method such as Basic (username/password) or Tokens.

If you choose to clone this repository as starting point you should remove this fake rest api from your payment gateway plugin!


## A note about compliance and security

This plugin does not aim to demonstrate or be an authority on data compliance. You should consult your gateways providers and, if necessary, legal counsel if you're unsure about how to ensure your gateway plugin is compliant and secure.


## I need help and support

If you're building your own payment gateway and you have questions:

+ Stop by `#developers` on the [LifterLMS Community Slack Channel](https://lifterlms.com/slack). Keep in mind that we're not going to build your payment gateway for you but we'll be excited to look at your code and help where possible.
+ Post a [new question here on GitHub](https://github.com/gocodebox/lifterlms-gateway-sample/issues/new?template=Question.md)!

_Note: LifterLMS has *two developers*. We very much wish to help every developer with a question but we will likely not respond as quickly as you would like us to respond. We appreciate your patience._


## I found a bug in this example plugin

Report it [here on GitHub](https://github.com/gocodebox/lifterlms-gateway-sample/issues/new?template=Bug_Report.md)!

**Do not Slack us about bugs if you haven't first opened a bug report here! We will ask you to report it in GitHub anyway!**


## I would like to contribute and improve this example plugin

We love you, please open up a [Pull Request](https://github.com/gocodebox/lifterlms-gateway-sample/pulls)!
