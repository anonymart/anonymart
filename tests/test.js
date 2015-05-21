module.exports = new (function() {

    var fs = require('fs')
        ,prompt = require('prompt')
        ,colors = require('colors')
        ,path = require('path')
        ,testCases = this


    if(!process.env.LS_BLOCKCHAIN_GUID)
        return console.log('LS_BLOCKCHAIN_GUID shell var missing'.red)

    if(!process.env.LS_BLOCKCHAIN_PASSWORD)
        return console.log('LS_BLOCKCHAIN_PASSWORD shell var missing'.red)
   
    console.info('Blockchain Guid:'.green,process.env.LS_BLOCKCHAIN_GUID)
    console.info('Blockchain Password:'.green,process.env.LS_BLOCKCHAIN_PASSWORD)


    testCases.after = function(client) {
        client.pause(1000000).end();
    };

    testCases['site setup'] = function (client) {
        client
            .url('http://localhost:8000')
            .setValue('[name=site_name]', 'Satoshi\'s Lemonade Stand')
            .setValue('[name=currency]', 'USD')
            .setValue('[name=blockchain_guid]',process.env.LS_BLOCKCHAIN_GUID)
            .setValue('[name=blockchain_password]',process.env.LS_BLOCKCHAIN_PASSWORD)
            .setValue('[name=withdrawl_minimum_btc]','0')
            .setValue('[name=address]','147BM4WmH17PPxhiH1kyNppWuyCAwn3Jm4')
            .setValue('[name=order_ttl_minutes]','30')
            .setValue('[name=site_info]','#Hello World\r\nWelcome to my Lemonade Stand!')
            .setValue('[name=pgp_public]','-----BEGIN PGP PUBLIC KEY BLOCK----- -----END PGP PUBLIC KEY BLOCK-----')
            .setValue('[name=password]','password')
            .setValue('[name=password_confirmation]','password')
            .submitForm('form')
            .assert.containsText("h1", "Hello World")
    };

    testCases['login'] = function (client) {
        client
            .url('http://localhost:8000/login')
            .setValue('[name=password]', 'password')
            .setValue('[name=captcha]', 'testing')
            .submitForm('form')
            .assert.urlEquals('http://localhost:8000/')
    };

    testCases['edit settings'] = function (client) {
        client
            .url('http://localhost:8000/settings/edit')
            .clearValue('[name=site_name]')
            .setValue('[name=site_name]', 'Satoshi\'s Lemonade and Cookie Stand')
            .submitForm('form')
            .assert.containsText('a.navbar-brand','Satoshi\'s Lemonade and Cookie Stand')
    };

    testCases['edit password'] = function (client) {
        client
            .url('http://localhost:8000/settings/edit')
            .setValue('[name=password]','password2')
            .setValue('[name=password_confirmation]','password2')
            .submitForm('form')
            .url('http://localhost/logout')
            .url('http://localhost:8000/login')
            .setValue('[name=password]', 'password')
            .setValue('[name=captcha]', 'testing')
            .submitForm('form')
            .assert.urlEquals('http://localhost:8000/login')
            .setValue('[name=password]', 'password2')
            .setValue('[name=captcha]', 'testing')
            .submitForm('form')
            .assert.urlEquals('http://localhost:8000/')
    };


    testCases['add 2 products'] = function (client) {
        client
            .url('http://localhost:8000/products/create')
            .setValue('[name=title]', 'Original Lemonade')
            .setValue('[name=info]', 'Our simple classic')
            .setValue('[name=amount_fiat]','.10')
            .setValue('#image',path.resolve(__dirname,'../assets/product_images/original.jpg'))
            .submitForm('form')
            .url('http://localhost:8000/products/create')
            .setValue('[name=title]', 'Rasberry Lemonade')
            .setValue('[name=info]', 'A tart twist')
            .setValue('[name=amount_fiat]','.10')
            .setValue('#image',path.resolve(__dirname,'../assets/product_images/rasberry.jpg'))
            .submitForm('form')
            .url('http://localhost:8000/')
            .execute(function(){
                return document.getElementsByClassName('product').length
            },[],function(outcome){
                client.assert.equal(outcome.value,2)
            })
    };

    testCases['edit a product'] = function (client) {
        client
            .url('http://localhost:8000/products/1/edit')
            .setValue('[name=title]', ' Updated')
            .setValue('[name=info]', ' updated')
            .clearValue('[name=amount_fiat]')
            .setValue('[name=amount_fiat]','.25')
            .submitForm('form')
            .url('http://localhost:8000/products/1')
            .assert.containsText("h1", "Original Lemonade Updated")
            .assert.containsText("#prices", ".25 USD")
    };

    testCases['archive a product'] = function (client) {
        client
            .url('http://localhost:8000/products/2/edit')
            .submitForm('#archiveForm')
            .url('http://localhost:8000')
            .execute(function(){
                return document.getElementsByClassName('product').length
            },[],function(outcome){
                client.assert.equal(outcome.value,1)
            })
    };


    testCases['logout'] = function (client) {
        client
            .url('http://localhost:8000/logout')
    };

    testCases['order a product'] = function (client) {
        client
            .url('http://localhost:8000/products/1/orders/create')
            .setValue('[name=text]', '-----BEGIN PGP MESSAGE----- -----END PGP MESSAGE-----')
            .setValue('[name=pgp_public]','-----BEGIN PGP PUBLIC KEY BLOCK----- -----END PGP PUBLIC KEY BLOCK-----')
            .setValue('[name=captcha]','testing')
            .submitForm('form')
            .assert.urlContains("/orders/1?code=")
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
            .url('http://localhost:8000/products/1/orders/create')
            .setValue('[name=text]', '-----BEGIN PGP MESSAGE----- -----END PGP MESSAGE-----')
            .setValue('[name=pgp_public]','-----BEGIN PGP PUBLIC KEY BLOCK----- -----END PGP PUBLIC KEY BLOCK-----')
            .setValue('[name=captcha]','testing')
            .submitForm('form')
            .url('http://localhost:8000/login')
            .setValue('[name=password]', 'password2')
            .setValue('[name=captcha]', 'testing')
            .submitForm('form')
            .url('http://localhost:8000/orders')
            .execute(function(){
                return document.getElementsByClassName('order').length
            },[],function(outcome){
                client.assert.equal(outcome.value,2)
            })
    };

    testCases['mark as shipped'] = function (client) {
        client
            .url('http://localhost:8000/orders/2')
            .submitForm('#markShippedForm')
            .url('http://localhost:8000/orders?status=shipped')
            .execute(function(){
                return document.getElementsByClassName('order').length
            },[],function(outcome){
                client.assert.equal(outcome.value,1)
            })

    };

    testCases['finishes without errors'] = function(client){
        var i;
        var count = 0;
        require('fs').createReadStream(path.resolve(__dirname,'../app/storage/logs/laravel.log'))
          .on('data', function(chunk) {
            for (i=0; i < chunk.length; ++i)
              if (chunk[i] == 10) count++;
          })
          .on('end', function() {
            client.assert.equal(count,1)
          });
    }


})();
