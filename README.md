# SymfonyTestProject

Technologies used:
	Platform - Windows 10
	Commandline tool - Git Bash
	Apache 2.4.46
	MySQL 5.7.31
	PHP 7.3.21
	Composer 1.10.10


Steps to install and use:

1. git clone https://github.com/utkarsh2k2/SymfonyTestProject.git
2. cd SymfonyTestProject
3. composer install
4. php bin/console doctrine:database:create
5. php bin/console doctrine:schema:create
6. php bin/console assets:install

Now you can go to the url http://localhost/SymfonyTestProject/public/index.php/admin/login and you should be able to see the login page

7. Create your first super admin user by running
   php bin/console fos:user:create --super-admin
   When prompted enter the username, email and password of your choosing, for you user

   Now you can login with this username and password and you will be redirected to dashboard

8. After logging in try to create a user with ROLE_EMPLOYEE by clicking on add user link on the dashboard
   Once on create user page, fill the required fields - username, email and plain password
   Then click on the Security tab on the create user page, once there, check the Enabled checkbox under Status block
   Now to assign role, under Roles block, check third checkbox from botton of the role list ( ROLE_EMPLOYEE: ROLE_SONATA_ADMIN, ROLE_USER, ROLE_APP_ADMIN_ORDER_DELETE, ROLE_SONATA_USER_ADMIN_USER_LIST, ROLE_SONATA_USER_ADMIN_USER_VIEW, ROLE_SONATA_USER_ADMIN_USER_CREATE, ROLE_SONATA_USER_ADMIN_USER_EDIT, ROLE_SONATA_USER_ADMIN_USER_DELETE)
   Finally click on Create button and the user with ROLE_EMPLYEE will be created. Logout of the super admin account by opening the top right dropdown and clicking logout. You can now try logging in with the recently created employee username and password.
   Note: ROLE_EMPLOYEE has complete access to both User and Order management modules. However while creating a user they can only assign roles equal to or below their privilage.

9. Similar to above step try creating a user with ROLE_USER, by selecting the role - ROLE_USER: ROLE_SONATA_ADMIN, ROLE_SONATA_USER_ADMIN_USER_VIEW, ROLE_APP_ADMIN_ORDER_LIST, ROLE_APP_ADMIN_ORDER_VIEW, ROLE_APP_ADMIN_ORDER_CREATE, ROLE_APP_ADMIN_ORDER_EDIT
   Note: ROLE_USER does not have access to the User management module. They can access Order management module but cannot delete an order.

10. Create a couple more users with ROLE_EMPLOYEE and then try logging in as user with ROLE_USER and create an Order. From the edit page of the order open the actions dropdown in the top bar and click on show action. Once there click on the Linking tab and notice the username of the employee assigned to this order.
    Upon creating a few more orders you will notice that an employee is randomly assigned to the order upon creation. For this I have used Doctrine event subscriber where the request listens to prePersist event of the Order object.


API
11. Copy these lines from .env.dist file to .env
    JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/privatekey.key
    JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/certificate.crt
    JWT_PASSPHRASE=gargantua.1 

    Note: The above keys and passphrase are created by me personally and for ease of demonstration I have added these ssl files to the repository, so you don't need to generate them again. In case these don't work for you, you can delete the existing files under SymfonyTest/Project/config/jwt/ folder and generate new ones by running below commands
	  - cd config/jwt/
	  - openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout privatekey.key -out certificate.crt
	    openssl command will ask you to enter some details and most importatly a passphrase of your choosing. Note down the passphrase that you enter, and after the files are created under ymfonyTest/Project/config/jwt/ folder, open .env file and replace gargantua.1 in line - JWT_PASSPHRASE=gargantua.1 , with your passphrase. That's it


12. For accessing the API endpoint to get list of all orders, you need to obtain a Jason web token either from command-line or browser.
    Command-line - 
	Note: You need to have the username and password of an existing user to successfully login and obtain a token
        curl -X POST -H "Content-Type: application/json" http://localhost/SymfonyTestProject/public/index.php/api/login_check -d '{"username":"yourusername","password":"yourpassword"}'
    Browser - 
	Note: For ease, you only need to have the username of an existing user to successfully login and obtain a token
	Run this url in the browser - http://localhost/SymfonyTestProject/public/index.php/get-token/yourusername

    Copy and save the returned token on a notepad which will be a JSON string eg. eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2MTU1OTc4NzgsImV4cCI6MTYxNTYwMTQ3OCwicm9sZXMiOlsiUk9MRV9TVVBFUl9BRE1JTiIsIlJPTEVfVVNFUiJdLCJ1c2VybmFtZSI6InV0a2Fyc2gyazIifQ.ojrthAuTsYXEtqTbP33v3LXySUyRt8jHzvg7dLGjXGl72fIiU0hVpRF_pIzbibAb8sTE7Y9X2BttcBCUOXAdYT9TPXM6m9aMJ-FDjLtPHVLdY3eUftbqo3sRwgnuK3RcG4Le56jFN9Nat8ehiFGjIReWXZKiiHgFwMVH4D61rj1CRtluHe9HJLusRKpYd834Us2IsDk9bM5ztfbXDged2TZGFW1-bWPWbT30WXAIx1IrGpuf6pKGG2o5i49DjFIZpRCy7oBRkRkZgGEX4mNSuF81XUZG9Fw0_zHQkYHkrYA1q2QyibSPSgf55ciHqVya5Ef2uVQK9sjcqjABA--zWA 

13. Now after you have obtained the token you can make a request to the API for obtaining the orders list in JSON format
    Note: Assuming you are using apache 2 server, you need to add a line - SetEnvIf Authorization "(.*)" HTTP_AUTHORIZATION=$1 , in your virtual host configuration ig.in httpd-vhosts.conf file, inside the <Directory></Directory> tag, on windows
    
    Command-line -
	curl -X GET -H "Content-Type: application/json" -H "Authorization: Bearer yourtoken" http://localhost/SymfonyTestProject/public/index.php/api/orders

    Browser - 
	For ease of demonstration you only need to know the username of an existing user to make a request through browser, and even the token will be automatically generated and passed. You do not need token for this request.
	Run this url in the browser - http://localhost/SymfonyTestProject/public/index.php/get-orders/yourusername

    Both above methods will fetch you the list of existing Orders from the database in JSON fromat
