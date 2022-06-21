# :sparkles: Wordpress Custom API Endpoint 
The WordPress front-end provides a default set of URL mappings, but the tools used to create them (e.g. the Rewrites API, as well as the query classes: WP_Query, WP_User, etc) are also available for creating your own URL mappings, or custom queries.

## :monocle_face: Installation 
1. Upload `wp-custom-api-endpoint.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

## :children_crossing: Usage 
1. :beers: Get all subscription 
```javascript
const settings = {
  "async": true,
  "crossDomain": true,
  "url": "/wp-json/dev-kabir/v1/shop-subscription",
  "method": "GET",
  "headers": {
    "Accept": "application/json"
  }
};

$.ajax(settings).done(function (response) {
  console.log(response);
});
```
2. :fire: Delete subscription 
```javascript
const settings = {
  "async": true,
  "crossDomain": true,
  "url": "/wp-json/dev-kabir/v1/shop-subscription/" + id,
  "method": "GET",
  "headers": {
    "Accept": "application/json"
  }
};

$.ajax(settings).done(function (response) {
  console.log(response);
});
```