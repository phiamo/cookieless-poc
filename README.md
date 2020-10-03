POC for cookieless affiliate tracking
=====

Due to ITP2 (Apple's intelligent tracking protection) third-party-cookies and
many other historically used tracking mechanisms don`t work anymore.

To overcome this there are several techniques available 

- bounceless
  - redirect to the target url directly without bouncing through third party cookies
- Server and Clientside Browser fingerprinting
  - Serverside fingerprinting, can not be as reliable as clientside
  - clientside fingerprinting is > 90% reliable 
- Servergenerated Cached Ids
  - having a api endpoint which enforces http caching to return the same server side generated id until the clients cache is forcefully cleared
- server2server tracking
  - having a postback url for conversion tracking

Not used here, could be investigated further:
- eTags
- AuthCache

Instructions for running
-----
You need to add to your hosts file since we emulate different domain to make sure we don`t have shared cookies etc:
```
127.0.0.1       affiliate.local
127.0.0.1       merchant.local
127.0.0.1       api.local
```
Get the code and install deps
```
git clone git@github.com:phiamo/cookieless-poc.git
cd cookieless-poc
composer install
```
We need a database, and currently sqlite is configured, so you need to run:
```
bin/console doctrine:database:create
bin/console doctrine:schema:up --force
```

Init browscap cache
```
bin/console app:init-browscap
```

And the code currently expects to be run on port 8000 without tls so e.g. run
```
export APP_ENV=dev; symfony serve  --no-tls
```

There will be two urls interesting for you:

http://affiliate.local:8000
This is where you should start and click on the buying link to be forwarded to the merchant

http://merchant.local:8000
This is the merchant page, which tries to reidentify our user directly in php, and as well via Javascript

Synopsis
-----
This POC uses bouceless paremeterized values to forward the identity on the first click to the merchant.

To track a user even if he comes back to a merchant without any tracking parameters in the url
we use a combined dependend approach, which tries to reidentify a user based on several values.
Currently we try to find one or more of the following identifiers:
 
- clientUUID (Servergenerated Cached Ids)
- clientside fingerprint
- server side fingerprint

We use here a wild combination of all methods available as a POC.

Conclusions
-----
- The Server generated cached id's are even available without forwarding it as a url parameter.
- The Client Side fingerprinting also does not need to be forwarded, since it can be regenerated at the merchants page
- The Server Side fingerprinting needs to be evaluated, if it is reliable in that context


Links and resources:
-----
For an overview about the topic i collected the resources here:

https://app.wink.rocks/user/phiamo/topics/fingerprinting 

Thats a continuously updated list of resources of this topic
