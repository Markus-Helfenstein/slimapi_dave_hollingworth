The same database like in Dave Hollingworth's Symfony tutorial may be used.  
Copy .env.example file, rename it .env and fill in database connection settings.

Add the following lines to your apache's C:\xampp\apache\conf\extra\httpd-vhosts.conf:

```
<VirtualHost *:80>
    ServerName slimapi.localhost
    DocumentRoot "C:/Users/YOUR_USERNAME/scoop/shims/slimapi_dave_hollingworth/public"

    <Directory "C:/Users/YOUR_USERNAME/scoop/shims/slimapi_dave_hollingworth/public">
        Require all granted
        AllowOverride All
    </Directory>
</VirtualHost>
```
