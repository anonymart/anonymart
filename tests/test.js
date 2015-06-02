module.exports = new (function() {

    var fs = require('fs')
        ,prompt = require('prompt')
        ,colors = require('colors')
        ,path = require('path')
        ,testCases = this
        ,baseUrl = 'http://localhost:8000'


    if(process.env.LS_ONION)
        baseUrl = 'http://'+process.env.LS_ONION

    console.info('Base Url:'.green,baseUrl)

    testCases.after = function(client) {
        client.pause(1000000).end();
    };

    testCases['site setup'] = function (client) {
        client
            .url(baseUrl)
            .setValue('[name=site_name]', 'Satoshi\'s Lemonade Stand')
            .setValue('[name=currency]', 'USD')
            .setValue('[name=order_ttl_minutes]','30')
            .setValue('[name=site_info]','#Hello World\r\nWelcome to my Lemonade Stand!')
            .setValue('[name=pgp_public]','-----BEGIN PGP PUBLIC KEY BLOCK----- -----END PGP PUBLIC KEY BLOCK-----')
            .setValue('[name=mpk]','xpub661MyMwAqRbcGK5eE2eSWmnU4Pg6knZZqZEmREAgZ4vj6z3B5soecps7UJj37NF9aWhjEMQoyH9xgcC14KUgEGX9avagrdv9rcN56wjwXR2')
            .setValue('[name=password]','password')
            .setValue('[name=password_confirmation]','password')
            .click('[name=is_testing]')
            .submitForm('form')
            .assert.containsText("h1", "Hello World")
    };

    testCases['login'] = function (client) {
        client
            .url(baseUrl+'/login')
            .setValue('[name=password]', 'password')
            .setValue('[name=captcha]', 'testing')
            .submitForm('form')
            .assert.urlEquals(baseUrl+'/')
    };

    testCases['edit settings'] = function (client) {
        client
            .url(baseUrl+'/settings/edit')
            .clearValue('[name=site_name]')
            .setValue('[name=site_name]', 'Satoshi\'s Lemonade and Cookie Stand')
            .submitForm('form')
            .assert.containsText('a.navbar-brand','Satoshi\'s Lemonade and Cookie Stand')
    };

    testCases['edit password'] = function (client) {
        client
            .url(baseUrl+'/settings/edit')
            .setValue('[name=password]','password2')
            .setValue('[name=password_confirmation]','password2')
            .submitForm('form')
            .url('http://localhost/logout')
            .url(baseUrl+'/login')
            .setValue('[name=password]', 'password')
            .setValue('[name=captcha]', 'testing')
            .submitForm('form')
            .assert.urlEquals(baseUrl+'/login')
            .setValue('[name=password]', 'password2')
            .setValue('[name=captcha]', 'testing')
            .submitForm('form')
            .assert.urlEquals(baseUrl+'/')
    };


    testCases['add 2 products'] = function (client) {
        client
            .url(baseUrl+'/products/create')
            .setValue('[name=title]', 'Original Lemonade')
            .setValue('[name=info]', 'Our simple classic')
            .setValue('[name=amount_fiat]','.10')
            .setValue('#image',path.resolve(__dirname,'../assets/product_images/original.jpg'))
            .submitForm('form')
            .url(baseUrl+'/products/create')
            .setValue('[name=title]', 'Rasberry Lemonade')
            .setValue('[name=info]', 'A tart twist')
            .setValue('[name=amount_fiat]','.15')
            .setValue('#image',path.resolve(__dirname,'../assets/product_images/rasberry.jpg'))
            .submitForm('form')
            .url(baseUrl+'/')
            .execute(function(){
                return document.getElementsByClassName('product').length
            },[],function(outcome){
                client.assert.equal(outcome.value,2)
            })
    };

    testCases['edit a product'] = function (client) {
        client
            .url(baseUrl+'/products/1/edit')
            .setValue('[name=title]', ' Updated')
            .setValue('[name=info]', ' updated')
            .clearValue('[name=amount_fiat]')
            .setValue('[name=amount_fiat]','.25')
            .submitForm('form')
            .url(baseUrl+'/products/1')
            .assert.containsText("h1", "Original Lemonade Updated")
            .assert.containsText("#prices", ".25 USD")
    };

    testCases['archive a product'] = function (client) {
        client
            .url(baseUrl+'/products/2/edit')
            .submitForm('#archiveForm')
            .url(baseUrl)
            .execute(function(){
                return document.getElementsByClassName('product').length
            },[],function(outcome){
                client.assert.equal(outcome.value,1)
            })
    };


    testCases['logout'] = function (client) {
        client
            .url(baseUrl+'/logout')
    };

    testCases['order a product'] = function (client) {
        client
            .url(baseUrl+'/products/1/orders/create')
            .setValue('[name=text]', '-----BEGIN PGP MESSAGE----- -----END PGP MESSAGE-----')
            .setValue('[name=pgp_public]','-----BEGIN PGP PUBLIC KEY BLOCK----- -----END PGP PUBLIC KEY BLOCK-----')
            .setValue('[name=captcha]','testing')
            .submitForm('form')
            .assert.urlContains('/orders/1?code=')
            .assert.containsText('#address','1N7V57yMjBFpLVsNRTAdXw7E2dWhswgBB6')
            .execute(function(){
                return document.getElementsByClassName('message').length
            },[],function(outcome){
                client.assert.equal(outcome.value,1)
            })
    };

    testCases['order another product with qty of 2'] = function (client) {
        client
            .url(baseUrl+'/products/1/orders/create')
            .clearValue('[name=quantity]')
            .setValue('[name=quantity]',2)
            .setValue('[name=text]', '-----BEGIN PGP MESSAGE----- -----END PGP MESSAGE-----')
            .setValue('[name=pgp_public]','-----BEGIN PGP PUBLIC KEY BLOCK----- -----END PGP PUBLIC KEY BLOCK-----')
            .setValue('[name=captcha]','testing')
            .submitForm('form')
            .assert.containsText('#address','1LVDNgEPc95Y9NfbjKYotUF7muEZaKa4mr')
            //.assert.containsText('#total_amount_btc','0.0020 BTC')
            .execute(function(){
                return document.getElementsByClassName('message').length
            },[],function(outcome){
                client.assert.equal(outcome.value,1)
            })
    };

    testCases['cancel an order'] = function (client) {
        client
            .submitForm('#markCancelledForm')
            .assert.containsText('h1','Cancelled')
            .execute(function(){
                return document.getElementsByClassName('message').length
            },[],function(outcome){
                client.assert.equal(outcome.value,2)
            })
    };

    testCases['make sure orders are added to the order list'] = function (client) {
        client
            .url(baseUrl+'/products/1/orders/create')
            .setValue('[name=text]', '-----BEGIN PGP MESSAGE----- -----END PGP MESSAGE-----')
            .setValue('[name=pgp_public]','-----BEGIN PGP PUBLIC KEY BLOCK----- -----END PGP PUBLIC KEY BLOCK-----')
            .setValue('[name=captcha]','testing')
            .submitForm('form')
            .url(baseUrl+'/login')
            .setValue('[name=password]', 'password2')
            .setValue('[name=captcha]', 'testing')
            .submitForm('form')
            .url(baseUrl+'/orders')
            .execute(function(){
                return document.getElementsByClassName('order').length
            },[],function(outcome){
                client.assert.equal(outcome.value,3)
            })
    };

    testCases['mark as shipped'] = function (client) {
        client
            .url(baseUrl+'/orders/2')
            .submitForm('#markShippedForm')
            .url(baseUrl+'/orders?status=shipped')
            .execute(function(){
                return document.getElementsByClassName('order').length
            },[],function(outcome){
                client.assert.equal(outcome.value,1)
            })

    };

    testCases['finishes without errors'] = function(client){
        client
            .url(baseUrl+'/logs/errors')
            .assert.visible('#noErrorsAlert')
    }


})();
