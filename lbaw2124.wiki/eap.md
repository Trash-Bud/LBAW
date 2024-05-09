# EAP: Architecture Specification and Prototype
## A7: Web Resources Specification
This artefact documents the  architecture of the web application to be developed, indicating the catalogue of resources, the properties of each resource, and the format of JSON responses. This specification adheres to the OpenAPI standard using YAML.
 
This artefact presents the documentation for Feup-Tech, including the CRUD (create, read, update, delete) operations for each resource.

### 1. Overview
An overview of the web application to implement is presented in this section, where the modules are identified and briefly described. The web resources associated with each module are detailed in the individual documentation of each module inside the OpenAPI specification.

|||
|-|-|
|M01: Authentication and Individual Profile| Web resources associated with user authentication and profiles. Includes the following system features: log in/ log out, registration, password recovery, viewing and editing profile information (account notifications, purchase history, personal recommendations...). |
|M02: Reviews and Wishlist| Web resources associated with reviews and wishlist. Includes the following system features: view included products, adding and removing products from the wishlist, making reviews on products bought previously and removing, reporting, listing and editing reviews.|
|M03: Products | Web resources associated with products. Includes the following system features: view products list and categories, view product details page, search for a given product, manage product’s information (description, category and stock).|
|M04: Shopping cart| Web resources associated with shopping carts. Includes the following system features: view included products, add/remove products and proceed to checkout.|
|M05: Order| Web resource associated with order. Includes the following system features: place, view/edit order information (address, payment details...), track and cancel.   |
|M06: Administration| Web resources associated with administration. Includes the following system features: viewing a user’s purchasing history, blocking and unblocking users, deleting user accounts, editing user profiles and managing warehouses.|
|M07: Static pages| Web resources with static content are associated with this module: about us, home, contacts and faq.|

### 2. Permissions
This section defines the permissions used in the modules to establish the conditions of access to resources.

||||
|-|-|-|
|PUB|Public|User with no privileges
|USR|User|Authenticated user
|BUY|Buyer|User that has bought a product
|ADM|Administrator|System administrators

### 3. OpenAPI Specification
This section includes the complete API specification in OpenAPI (YAML).
Additionally there is a link to the OpenAPI YAML file in the group's repository.

[Feup-Tech OpenApi specifications](https://git.fe.up.pt/lbaw/lbaw2122/lbaw2124/-/blob/9f79a818eca9faf478141407fa98aef9a6ff34e5/openapi.yaml)

```YAML
openapi: '3.0.2'
info:
  title: FeupTech
  version: '1.0'
  description: 'Web Resources Specification (A7) for FeupTech'
servers:
  - url: http://lbaw2224.lbaw.fe.up.pt
    description: 'Production Server'
externalDocs:
 description: Find more info here.
 url: 
tags:
 - name: 'M01: Authentication and Individual Profile'
 - name: 'M02: Reviews and Wishlist'
 - name: 'M03: Products'
 - name: 'M04: Shopping Cart'
 - name: 'M05: Order'
 - name: 'M06: Administration'
 - name: 'M07: Static Pages'
paths:

  ###---------------------------M01---------------------------###

  /login:
    get:
      operationId: R101
      summary: 'R101: Login Form'
      description: 'Provide login form. Access: PUB'
      tags:
        - 'M01: Authentication and Individual Profile'
      responses:
        '200':
          description: 'Ok. Show Log-in UI'
    post:
      operationId: R102
      summary: 'R102: Login Action'
      description: 'Processes the login form submission. Access: PUB'
      tags:
        - 'M01: Authentication and Individual Profile'

      requestBody:
        required: true
        content:
          application/x-www-form-urlencoded:
            schema:
              type: object
              properties:
                email:       # <!--- form field name
                  type: string
                  format: email
                password:    # <!--- form field name
                  type: string
                  format: password
              required:
                    - email
                    - password

      responses:
        '302':
          description: 'Redirect after processing the login credentials.'
          content:
            application/json:
              schema:
                type: string
              examples:
                Success:
                  description: 'Successful authentication. Redirect to user profile.'
                  value: '/personalinfo'
                Error:
                  description: 'Failed authentication. Redirect to login form.'
                  value: '/login'
  /logout:
    post:
      operationId: R103
      summary: 'R103: Logout Action'
      description: 'Logout the current user. Access: USR, ADM'
      tags:
        - 'M01: Authentication and Individual Profile'
      responses:
        '302':
          description: 'Successful logout. Redirect to /login.'

  /register:
   get:
     operationId: R104
     summary: 'R104: Register Form'
     description: 'Provide new user registration form. Access: PUB'
     tags:
       - 'M01: Authentication and Individual Profile'
     responses:
       '200':
         description: 'Ok. Show Sign-Up UI'
   post:
     operationId: R105
     summary: 'R105: Register Action'
     description: 'Processes the new user registration form submission. Access: PUB'
     tags:
       - 'M01: Authentication and Individual Profile'

     requestBody:
       required: true
       content:
         application/x-www-form-urlencoded:
           schema:
             type: object
             properties:
               name:
                 type: string
               email:
                 type: string
                 format: email
               password: 
                 type: string
                 format: password
               confirm password: 
                 type: string
                 format: password
             required:
               - email
               - password
               - confirm password
               - name
     responses:
       '302':
         description: 'Redirect after processing the new user information.'
         content:
           application/json:
             schema:
               type: string
             examples:
               Success:
                 description: 'Successful authentication. Redirect to user profile.'
                 value: '/personalinfo'
               Failure:
                 description: 'Failed authentication. Redirect to login form.'
                 value: '/login'
  /password_recovery: 
        get: 
          operationId: R106
          summary: 'R106: Password Recovery form'
          description: 'Show password recovery form. Access: PUB'
          tags:
            - 'M01: Authentication and Individual Profile'
          responses:
            '200':
                  description: 'Ok. Show password recovery form'
        post: 
          operationId: R107
          summary: 'R107: Password Recovery request'
          description: 'File a password recovery request. Access: PUB'
          tags: 
             - 'M01: Authentication and Individual Profile'
          requestBody:
            required: true
            content:
              application/x-www-form-urlencoded:
                schema:
                  type: object
                  properties:
                    email:       # <!--- form field name
                      type: string
                      format: email
                  required:
                        - email
          responses:
              '200':
                description: 'Redirect after processing the new user information.'
                content:
                  application/json:
                    examples:
                      Success:
                        description: 'Email exists.'
                      Failure:
                        description: 'Email does not exist.'

  /personalinfo:
     get:
      operationId: R108
      summary: 'R108: View User Profile'
      description: 'Show the individual user profile. Access: USR'
      tags:
        - 'M01: Authentication and Individual Profile'

      responses:
        '200':
          description: 'Ok. Show User Profile UI'
     post:
      operationId: R109
      summary: 'R109: Appeal Unblock'
      description: 'Appeal to the administrators to be able to make reviews again. Access: USR'
      tags:
        - 'M01: Authentication and Individual Profile'
      responses:
          '200':
            description: 'Ok. Request in queue.'

     delete:
      operationId: R109
      summary: 'R109: Delete User Profile'
      description: 'Delete the individual user profile. Access: USR'
      tags:
        - 'M01: Authentication and Individual Profile'
      requestBody:
        required: true
        content:
          application/x-www-form-urlencoded:
            schema:
              type: object
              properties:
                current password:
                  type: string
                  format: password
              required:
                    - current password
      responses:
          '302':
            description: 'Evaluate if account should be deleted.'
            content:
                  application/json:
                    examples:
                      Success:
                        description: 'Password accepted. Redirect to /products.'
                      Failure:
                        description: 'Password rejected. Redirect to /personainfo.'
          '404': 
            description: 'Profile not found.'
     patch:
      operationId: R110
      summary: 'R110: Edit User Profile Request'
      description: 'File a user profile editon recovery request. Access: USR'
      tags: 
        - 'M01: Authentication and Individual Profile'
      requestBody:
            required: true
            content:
              application/x-www-form-urlencoded:
                schema:
                  type: object
                  properties:
                    new picture:
                      type: string
                      format: binary
                    new name:
                      type: string
                    new email:
                      type: string
                      format: email
                    new password:
                      type: string
                      format: password
                    confirm new password:
                      type: string
                      format: password
                    current password:       # <!--- form field name
                      type: string
                      format: password
                  required:
                        - current password
      responses:
              '200':
                description: 'Redirect after processing the new information.'
                content:
                  application/json:
                    examples:
                      Success:
                        description: 'Update.'
                        
                      Failure:
                        description: 'Incorrect current password. Do not update'
                        
  /personalinfo/redeem:
    get:
      operationId: R111
      summary: 'R111: Redeem Gif Card Form'
      description: 'Show the redeem giftcard form. Access: USR'
      tags:
        - 'M01: Authentication and Individual Profile'
      responses:
        '200':
          description: 'Ok. Showing redeem form'
    patch:
      operationId: R112
      summary: 'R112: Redeem Git Card Request'
      description: 'Sends the server a giftcard code to validate. Access: USR'
      tags: 
        - 'M01: Authentication and Individual Profile'
      requestBody:
            required: true
            content:
              application/x-www-form-urlencoded:
                schema:
                  type: object
                  properties:
                    code:
                      type: string
                  required:
                        - code
      responses:
              '200':
                description: 'Sent request to validate code.'
                content:
                    application/json:
                      examples:
                        Success:
                          description: 'Code accepted. Added account credits.'
                        Failure:
                          description: 'Code rejected. No account credits were added.'
        
  /addresses:
    get:
      operationId: R113
      summary: 'R113: View User Adresses'
      description: 'Lists all the users adresses. Access: USR'
      tags:
        - 'M01: Authentication and Individual Profile'
      responses:
        '200':
          description: 'Ok. Show User Adresses UI'

  /delete_address/{id}:
    delete:
      operationId: R114
      summary: 'R114: Delete Address'
      description: 'Removes an address from the users profile. Access: USR'
      tags: 
        - 'M01: Authentication and Individual Profile'
      requestBody:
            required: true
            content:
              application/x-www-form-urlencoded:
                schema:
                  type: object
                  properties:
                    address id:
                      type: string
                  required:
                        - address id
      responses:
              '200':
                description: 'Remove the address.'

  /add_address:
    get:
      operationId: R115
      summary: 'R115: Adress Input Form'
      description: 'Shows the form to add an address. Access: USR'
      tags:
        - 'M01: Authentication and Individual Profile'
      responses:
        '200':
          description: 'Ok. Show Add Adresses UI'
    post:
      operationId: R116
      summary: 'R116: Adress Input Request'
      description: 'Adds a new address to the users profile. Access: USR'
      tags: 
        - 'M01: Authentication and Individual Profile'
      requestBody:
            required: true
            content:
              application/x-www-form-urlencoded:
                schema:
                  type: object
                  properties:
                    street:
                      type: string
                    country:
                      type: string
                    postal code:
                      type: string
                  required:
                    - street
                    - country
                    - postal code
      responses:
              '200':
                description: 'Add the new adress.'

  /edit_address/{id}:
    get:
      operationId: R117
      summary: 'R117: Adress Edit Form'
      description: 'Shows the form to edit a specific address. Access: USR'
      tags:
        - 'M01: Authentication and Individual Profile'
      parameters:
        - in: path
          name: id
          schema:
            type: integer
          required: true  
      responses:
        '200':
          description: 'Ok. Show Add Adresses UI'
    patch:
      operationId: R118
      summary: 'R118: Address Edit Request'
      description: 'Edit an address from the users profile. Access: USR'
      tags: 
        - 'M01: Authentication and Individual Profile'
      requestBody:
            required: true
            content:
              application/x-www-form-urlencoded:
                schema:
                  type: object
                  properties:
                    street:
                      type: string
                    country:
                      type: string
                    postal code:
                      type: string
      responses:
              '200':
                description: 'Update the adress.'

  /payment_methods:
    get:
      operationId: R119
      summary: 'R119: View Payment Methods'
      description: 'Lists all the users payment methods. Access: USR'
      tags:
        - 'M01: Authentication and Individual Profile'
      responses:
        '200':
          description: 'Ok. Show User payment methods UI'
    delete:
      operationId: R120
      summary: 'R120: Delete Payment Method'
      description: 'Removes a payment method from the users profile. Access: USR'
      tags: 
        - 'M01: Authentication and Individual Profile'
      responses:
              '200':
                description: 'Remove the payment method.'
  
  /add_payment_method:
     get:
      operationId: R121
      summary: 'R121: Payment Method Input Form'
      description: 'Shows the form to add a payment method . Access: USR'
      tags:
        - 'M01: Authentication and Individual Profile'
      responses:
        '200':
          description: 'Ok. Show User payment methods UI'
     post:
      operationId: R122
      summary: 'R122: Payment Method Input Request'
      description: 'Adds a new payment method to the users profile. Access: USR'
      tags: 
        - 'M01: Authentication and Individual Profile'
      requestBody:
            required: true
            content:
              application/x-www-form-urlencoded:
                schema:
                  type: object
                  properties:
                    type:
                      type: string
                    number:
                      type: string
                    expiration date:
                      type: string
                    cvc:
                      type: string
                  required:
                    - cvc
                    - type
                    - number
                    - expiration date
      responses:
              '200':
                description: 'Add the new payment method.'
                
  /orders:
     get:
      operationId: R123
      summary: 'R123: View Orders'
      description: 'Lists all orders made by the user and their status. Access: USR'
      tags:
        - 'M01: Authentication and Individual Profile'
      responses:
        '200':
          description: 'Ok. Show User orders list'
        '403': 
          description: 'Not enough permissions.'

  /recomendations:
     get:
      operationId: R124
      summary: 'R124: View Recommendations'
      description: 'Lists product recommendations based on the users purchases. Access: USR'
      tags:
        - 'M01: Authentication and Individual Profile'
      responses:
        '200':
          description: 'Ok. Show Users recommendations'
        '403': 
          description: 'Not enough permissions.'
 


  /reviews:
    get:
      operationId: R125
      summary: 'R125: View Reviews'
      description: 'List all reviews made by the user. Access: USR'
      tags:
        - 'M01: Authentication and Individual Profile'
      parameters:
        - in: path
          name: id
          schema:
            type: integer
          required: true
      responses:
        '200':
          description: 'Ok. Show User reviews list'
  

  /notifications:
    get:
      operationId: R126
      summary: 'R126: View Notifications'
      description: 'List all notifications received by the user. Access: USR'
      tags:
        - 'M01: Authentication and Individual Profile'

      responses:
        '200':
          description: 'Ok. Show User notifications list'
        
  ###---------------------------M02---------------------------###

  /wishlist:
    get:
      operationId: R201
      summary: 'R201: View Wishlist'
      description: 'View the individual user profile wishlist. Access: USR'
      tags:
        - 'M02: Reviews and Wishlist'
      responses:
       '200':
         description: 'Ok. Show wishlist.'
    delete:
      operationId: R202
      summary: 'R202: Delete Product from Wishlist'
      description: 'Remove product from the individual user profile wishlist. Access: USR'
      tags:
        - 'M02: Reviews and Wishlist'
      requestBody:
            required: true
            content:
              application/x-www-form-urlencoded:
                schema:
                  type: object
                  properties:
                    product id:
                      type: integer
                    user id:
                      type: integer
                  required:
                        - product id
                        - user id
      responses:
       '200':
         description: 'Ok. Remove product.'
  
  /add_wishlist/{id}:
    post:
    
      operationId: R203
      summary: 'R203: Add Product to Wishlist'
      description: 'Add product to the individual user profile wishlist. Access: USR'
      tags:
        - 'M02: Reviews and Wishlist'
      parameters:
        - in: path
          name: id
          schema:
            type: integer
          required: true
      responses:
       '200':
         description: 'Ok. Add product.'

  /review/{id}:
      get:
        operationId: R204
        summary: 'R204: View Review'
        description: 'View the review. Access: PUB'
        tags:
          - 'M02: Reviews and Wishlist'
        parameters:
        - in: path
          name: id
          schema:
            type: integer
          required: true
        responses:
          '200':
            description: 'Ok. Show reviews.'
      delete:
        operationId: R205
        summary: 'R205: Delete Review'
        description: 'Remove a previously made review of a purchased product. Access: BUY, ADM'
        tags:
          - 'M02: Reviews and Wishlist'
        parameters:
          - in: path
            name: id
            schema:
              type: integer 
            required: true
        responses:
          '302':
            description: 'Delete after verifying permissions and Redirect to the product.'
          '403':
            description: 'Forbidden action'
      post:
        operationId: R206
        summary: 'R206: Report Review'
        description: 'Report a review made by another user. Access: USR'
        tags:
          - 'M02: Reviews and Wishlist'
        parameters:
        - in: path
          name: id
          schema:
            type: integer
          required: true
        responses:
          '200':
            description: 'Ok. '

  /add_review/{id}:
      get:
        operationId: R207
        summary: 'R207: Review Input Form'
        description: 'Show the form for adding reviews. Access: BUY'
        tags:
          - 'M02: Reviews and Wishlist'
        parameters:
          - in: path
            name: id
            schema:
              type: integer 
            required: true
        responses:
          '200':
            description: 'Ok. Show review form.'
      post:
        operationId: R208
        summary: 'R208: Review Input Request'
        description: 'Add a review to a purchased product. Access: BUY'
        tags:
          - 'M02: Reviews and Wishlist'
        parameters:
          - in: path
            name: id
            schema:
              type: integer 
            required: true
        requestBody:
            required: true
            content:
              application/x-www-form-urlencoded:
                schema:
                  type: object
                  properties:
                    rating:
                      type: integer
                    description:
                      type: string
                  required:
                      - rating
        responses:
          '200':
            description: 'Ok. Post new review.'
          '403':
            description: 'Frobidden action.'

  /edit_review/{id}:
      get:
        operationId: R209
        summary: 'R209: Review Edit Form'
        description: 'Show the form for adding reviews. Access: BUY'
        tags:
          - 'M02: Reviews and Wishlist'
        parameters:
          - in: path
            name: id
            schema:
              type: integer
            required: true
        responses:
          '200':
            description: 'Ok. Show review form.'
      patch:
        operationId: R210
        summary: 'R210: Review Edit Request'
        description: 'Edit a previously made review. Access: BUY'
        tags:
          - 'M02: Reviews and Wishlist'
        parameters:
          - in: path
            name: id
            schema:
              type: integer
            required: true
        requestBody:
            required: true
            content:
              application/x-www-form-urlencoded:
                schema:
                  type: object
                  properties:
                    rating:
                      type: integer
                    description:
                      type: string
        responses:
          '200':
            description: 'Ok. Edit review.'
          '403':
            description: 'Frobidden action.'

  ###---------------------------M03---------------------------###

  /products:
      get:
        operationId: R301
        summary: 'R301: View Products'
        description: 'List all the products of the website. Access: PUB'
        tags:
          - 'M03: Products'
        responses:
          '200':
            description: 'Ok. Show products.'
            
  /categories:
      get: 
        operationId: R302
        summary: 'R302: View Categories.'
        description: 'List all categories that are available in store. Access: PUB'
        tags: 
          - 'M03: Products'
        responses: 
          '302': 
            description: 'Redirect to the list all products page with the category filter'
      

  /products/{category}: 
      get: 
        operationId: R303
        summary: 'R303: View Products of a Category.'
        description: 'List all products that that are included in a category'
        tags: 
          - 'M03: Products'
        parameters:
        - in: path
          name: category
          schema:
            type: string 
          required: true
        responses:
          '200': 
            description: 'Ok. Successful listing of products'
          '404': 
            description: 'The category is not present in the store'

  /products/search:
      get:
        operationId: R304
        summary: 'R304: Search Product'
        description: 'Search a specific product of the website. Access: PUB'
        tags:
          - 'M03: Products'
        parameters:
          - in: path
            name: searchString
            schema:
              type: string 
            required: true
        responses:
          '201':
            description: 'Ok. Show search results.'

  /products/{id}:
      get:
        operationId: R305
        summary: 'R305: View Product Details.'
        description: 'Show detailed information a specific product. Access: PUB'
        tags:
          - 'M03: Products'
        parameters:
          - in: path
            name: id
            schema:
              type: integer 
            required: true
        responses:
          '200':
            description: 'Ok. Show product.'
          '404': 
            description: 'No product found.'
      delete: 
        operationId: R306
        summary: 'R306: Reduce Item Stock'
        description: 'Decreases stock of product to 0. Access: ADM'
        tags:
            - 'M03: Products'
        parameters:
          - in: path
            name: id
            schema:
              type: integer 
            required: true
        responses: 
          '302':
              description: 'Successful remove. Redirect to /products.'
          '403': 
            description: 'The user does not have permissions to perfom the action'  

  /add_product:
    get:
        operationId: R307
        summary: 'R307: Item Input Form'
        description: 'Form that adds item to the website. Access: ADM'
        tags:
          - 'M03: Products'
        responses:
          '200':
            description: 'Ok. Provided form'
          '403': 
            description: 'The user does not have permissions to perfom the action'
    post: 
      operationId: R308
      summary: 'R308: Item Input Request'
      description: 'Add a given item of the items database. Access: ADM'
      tags:
          - 'M03: Products'
      requestBody:
        required: true
        content:
          application/x-www-form-urlencoded:
            schema:
              type: object
              properties:
                category:
                  type: string
                description: 
                  type: string
                picture:
                  type: string
                  format: binary
                stock: 
                  type: integer
                price: 
                  type: number
                  format: float
                original_price: 
                  type: number
                  format: float
                on_sale:
                  type: number
                  format: float
                code: 
                  type: integer
              required:
                - category 
                - stock 
                - original_price
                - code
                - on_sale 

      responses: 
        '302':
          description: 'Successful insert. Redirect to /products/add.'
        '403': 
          description: 'The user does not have permissions to perfom the action'

  /edit_product/{id}:
    get:
        operationId: R309
        summary: 'R309: Item Edit Form'
        description: 'Form that allows the admin to edit an item from the website. Access: ADM'
        tags:
         - 'M03: Products'
        parameters:
        - in: path
          name: id
          schema:
            type: integer
          required: true
        responses:
          '200':
            description: 'Ok. Edited the specified product'
    patch: 
      operationId: R310
      summary: 'R310: Item Edit Request'
      description: 'Edits a given item of the items database. Access: ADM'
      tags:
          - 'M03: Products'
      requestBody:
        required: true
        content:
          application/x-www-form-urlencoded:
            schema:
              type: object
              properties:
                category:
                  type: string
                description: 
                  type: string
                picture:
                  type: string
                  format: binary
                stock: 
                  type: integer
                price: 
                  type: number
                  format: float
                original_price: 
                  type: number
                  format: float
                on_sale:
                  type: number
                  format: float
              required:
                - id
                - admin id
      responses: 
        '302':
          description: 'Successful authentication. Redirect to /products.'
        '403': 
          description: 'The user does not have permissions to perfom the action'
    
  ###---------------------------M04---------------------------###

  /shoppingcart:
      get:
        operationId: R401
        summary: 'R401: View Shopping Cart'
        description: 'List all the products on the users shopping cart. Access: USR'
        tags:
          - 'M04: Shopping Cart'
        responses:
          '200':
            description: 'Ok. Show shopping cart.'
  /shoppingcart/{id}:
      post:
        operationId: R402
        summary: 'R402: Add Product to Shopping Cart'
        description: 'Add an ammount of a product to the shopping cart. Access: PUB, USR'
        tags:
          - 'M04: Shopping Cart'
        requestBody:
          required: true
          content:
            application/x-www-form-urlencoded:
              schema:
                type: object
                properties:
                  ammount:
                    type: integer
                    default: 1
        responses:
          '200':
            description: 'Ok. Add product'
      delete:
        operationId: R403
        summary: 'R403: Delete Product from Shopping Cart'
        description: 'Remove product from the users shopping cart. Access: PUB, USR'
        tags:
          - 'M04: Shopping Cart'
        requestBody:
          required: true
          content:
            application/x-www-form-urlencoded:
              schema:
                type: object
                properties:
                  ammount:
                    type: integer
                    default: 1
        responses:
          '200':
            description: 'Ok. Remove product from shopping cart.'

  /checkout:
      get:
        operationId: R404
        summary: 'R404: Checkout Form'
        description: 'Shows information about the order about to be placed based on the contents of the shopping cart. Access: USR'
        tags:
          - 'M04: Shopping Cart'
        responses:
          '200':
            description: 'Ok. Show checkout page'
      post:
        operationId: R405
        summary: 'R405: Checkout Request'
        description: 'Choice to place an order  or not. Access: USR'
        tags:
          - 'M04: Shopping Cart'
        responses:
          '302':
            description: 'Redirect after the user decides if they want to proceed.'
            content:
              application/json:
                schema:
                  type: string
                examples:
                  Proceed:
                    description: 'User added a payment method. Redirect to order.'
                    value: '/order/{id}'
                  Cancel:
                    description: 'User did not add a payment method. Redirect to shoppingCart.'
                    value: '/shoppingCart'

  ###---------------------------M05---------------------------###

  /order/{id}:
      get:
        operationId: R501
        summary: 'R501: View Order'
        description: 'View information of a specific order (address, payment details...). Access: BUY, ADM'
        tags:
          - 'M05: Order'
        parameters:
          - in: path
            name: id
            schema:
              type: integer
            required: true
        responses:
          '200':
            description: 'Ok. Show order.'
      delete:
        operationId: R502
        summary: 'R502: Cancel Order'
        description: 'Cancel a specific order. Access: BUY'
        tags:
          - 'M05: Order'
        parameters:
          - in: path
            name: id
            schema:
              type: integer
            required: true
        responses:
          '302':
            description: 'Cancel order. Redirect to /user/{id} '

  /edit_order/{id}:
      get:
        operationId: R503
        summary: 'R503: Order Edit Form'
        description: 'Show form for editing an order. Access: BUY, ADM'
        tags:
          - 'M05: Order'
        parameters:
          - in: path
            name: id
            schema:
              type: integer
            required: true
        responses:
          '200':
            description: 'Ok. Show the edit order form'
      post:
        operationId: R504
        summary: 'R504: Order Edit Request'
        description: 'Edit information of a specific order (address, payment details...). Access: BUY, ADM'
        tags:
          - 'M05: Order'
        parameters:
          - in: path
            name: id
            schema:
              type: integer
            required: true
        requestBody:
          required: true
          content:
            application/x-www-form-urlencoded:
              schema:
                type: object
                properties:
                  charge id:
                    type: integer
                  address id:
                    type: integer
                  status:
                    type: string
                required:
                  - user id
        responses:
          '302':
            description: 'Edit order. Redirect to /order/{id}'

  /order/{trackId}:
      get:
        operationId: R505
        summary: 'R505: Track Order'
        description: 'Sends user to the tracking order page. Access: BUY'
        tags:
          - 'M05: Order' 
        parameters:
          - in: path
            name: trackId
            schema:
              type: integer
            required: true
        responses:
          '200':
            description: 'Ok. Show track page.'
          '402':
            description: 'Forbidden access.'

  ###---------------------------M06---------------------------###
  
  /admin: 
    get: 
      operationId: R601
      summary: 'R601: View Admin dashboard'
      description: 'Admin dashboard page. Access: ADM'
      tags:
          - 'M06: Administration' 
      responses:
        '200':
            description: 'Ok. Showing Admin dashboard' 
        '403':
            description: 'Forbidden, not enough permissions'
  
  /users:
      get:
        operationId: R602
        summary: 'R602: View Users'
        description: 'List all users. Access: ADM'
        tags:
          - 'M06: Administration'
        responses:
          '200': 
            description: 'Ok. Showing all users and available options.'
  /user/{id}/block:
      post:
        operationId: R603
        summary: 'R603: Block User'
        description: 'Block a user from the website. Access: ADM'
        tags:
          - 'M06: Administration'
        parameters:
          - in: path
            name: id
            schema:
              type: integer
            required: true
        responses:
          '200':
            description: 'Ok. Block or Unblock user. '
          '403': 
            description: 'The user does not have permissions to perfom the action'

  /user/{id}/unblock:
      post:
        operationId: R604
        summary: 'R604: Unblock User'
        description: 'Unblock a user from the website. Access: ADM'
        tags:
          - 'M06: Administration'
        parameters:
          - in: path
            name: id
            schema:
              type: integer
            required: true
        responses:
          '200':
            description: 'Ok. Block or Unblock user. '
          '403': 
            description: 'The user does not have permissions to perfom the action'
      

  /user/{id}/edit:
      get:
        operationId: R605
        summary: 'R605: User Profile Edit Form'
        description: 'Get the user edition form. Access: ADM'
        tags:
          - 'M06: Administration'
        responses:
          '200': 
            description: 'Ok. Showing user edition form.'
      post:
        operationId: R606
        summary: 'R606: User Profile Edit Request'
        description: 'Edit profile of a specific user. Access: ADM'
        tags:
          - 'M06: Administration'
        requestBody:
          required: true
          content:
            application/x-www-form-urlencoded:
              schema:
                type: object
                properties:
                  image:
                    type: string
                    format: bytes
                  name:
                    type: string
                  email:
                    type: string
                    format: email
                  current password:
                    type: string
                    format: password
                required:
                  - current password
        responses:
          '302':
            description: 'Redirecting to the user edit profile page /user/{id}/edit.'
          '403': 
            description: 'The user does not have permissions to perfom the action'

  /warehouses:
    get: 
      operationId: R607
      summary: 'R607: List all warehouses'
      description: 'List all warehouses currently selling in the store. Access: ADM'
      tags:
          - 'M06: Administration' 
      responses:
        '200':
            description: 'Ok. Showing all warehouses' 
        '403':
            description: 'Forbidden, not enough permissions'

  /warehouses/{warehouseId}:
    get: 
      operationId: R608
      summary: 'R608: List all products of a warehouse'
      description: 'List all the products os the chosen warehouse. Access: ADM'
      tags:
          - 'M06: Administration' 
      parameters:
          - in: path
            name: warehouseId
            schema:
              type: integer
            required: true
      responses:
        '200':
            description: 'Ok. Showing warehouse products' 
        '403':
            description: 'Forbidden, not enough permissions'
    delete:
      operationId: R609
      summary: 'R609: Delete warehouse'
      description: 'Provides the admin with a form of deleting warehouses. Access: ADM'
      tags:
          - 'M06: Administration' 
      responses:
        '200':
            description: 'Ok. Showing the form.' 
        '403':
            description: 'Forbidden, not enough permissions.'

  /add_warehouse:
    get: 
      operationId: R610
      summary: 'R610: Get warehouse addition form'
      description: 'Provides the admin with a form for warehouse creation. Access: ADM'
      tags:
          - 'M06: Administration' 
      responses:
        '200':
            description: 'Ok. Showing the form.' 
        '403':
            description: 'Forbidden, not enough permissions.'
    post: 
        operationId: R611
        summary: 'R611: Add a new warehouse'
        description: 'Sends warhouse information to the server, for it to be inserted in the databse. Access: ADM'
        tags:
          - 'M06: Administration'
        requestBody:
          required: true
          content:
            application/x-www-form-urlencoded:
              schema:
                type: object
                properties:
                  code :
                    type: integer
                  location :
                    type: integer
                  postal_code :
                    type: integer
                required:
                  - code 
                  - location 
                  - postal_code 
        responses:
          '302':
            description: 'Redirecting to the list warehouses page /admin/{id}/warehouses.'
          '403': 
            description: 'The user does not have permissions to perfom the action'
        
  ###---------------------------M07---------------------------###

  /home:
      get:
        operationId: R701
        summary: 'R701: Home page'
        description: 'Home page view. Access: PUB'
        tags:
          - 'M07: Static Pages' 
        responses:
          '200':
            description: 'Ok. Showing home page'  
  /contacts:
      get:
        operationId: R702
        summary: 'R702: Contacts page'
        description: 'Contains helpful contacts. Access: PUB'
        tags:
          - 'M07: Static Pages' 
        responses:
          '200':
            description: 'Ok. Showing contacts page'  
  /faq:
      get:
        operationId: R703
        summary: 'R703: Faq page'
        description: 'Faq page view. Access: PUB'
        tags:
          - 'M07: Static Pages' 
        responses:
          '200':
            description: 'Ok. Showing faq page' 
             
  /about_us:
      get:
        operationId: R704
        summary: 'R704: About us page'
        description: 'About us page view, useful information about the company. Access: PUB'
        tags:
          - 'M07: Static Pages' 
        responses:
          '200':
            description: 'Ok. Showing About us page'       
```
## A8: Vertical prototype
The Vertical Prototype includes the implementation of the features marked as necessary (with an asterisk) in the common and theme requirements documents. This artefact aims to validate the architecture presented, also serving to gain familiarity with the technologies used in the project.

The implementation is based on the LBAW Framework and includes work on all layers of the architecture of the solution to implement: user interface, business logic and data access. The prototype includes the implementation of pages of visualization, insertion, edition and removal of information, the control of permissions in the access to the implemented pages, and the presentation of error and success messages.

### 1. Implemented Features
#### 1.1. Implemented User Stories

The user stories that were implemented in the prototype are described in the following table.

|User Story| Name| Priority| Description|
|-|-|-|-|
|US001|View Product List|high|As a User, I want to be able to view the products available in the store, so that I can know what I am able to purchase. [FR101]|
|US003 | View Product Details| high| As a User, I want to be able to view product details, so that I know more about a product before deciding to purchase it. [FR103]|
|US004|View Product Reviews|high|As a User, I want to be able to view product reviews, so that I can know what other people’s experience with the product was, before deciding to purchase it. [FR104]|
|US005|Add Product to Shopping Car|high|As a User, I want to be able to add products to the shopping cart, so that I can have all the products I wish to buy in one easy to access place. [FR105]|
|US006|Manage Shopping Cart|high|As a User, I want to be able to manage the shopping cart, so that I can remove any products I no longer want or change the amount of a certain product that I want. [FR106]|
|US007|Search Products|high|As a User, I want to be able to search for products, so that I can easily find a specific product I want to purchase. [FR107]|
|US101|Sign-in |high |As a Non-Authenticated User, I want to authenticate into the system, so that I can buy products and manage my profile. [FR011]||US102 |Sign-up |high |As a Non-Authenticated User, I want to register myself into the system, so that I can authenticate myself into the system.  [FR012] |
|US201|View profile|high|As an Authenticated User, I want to be able to view my public and private profile settings and information, so that I know what information I am currently making publicly available.  [FR021]|
|US202|Manage profile|high|As an Authenticated User, I want to manage all my account details (such as email, password, address, picture, preference settings, …) so that I can change them if they are no longer up to date.  [FR022/23]|
|US204|Log Out|high|As an Authenticated User, I want to be able to log out of my account, so that other people using the device can’t use it.|
|US205|Go to checkout page|high|As an Authenticated User, I want to go to my checkout page, so that I can place an order.|
|US206|Complete purchase/Checkout|high|As an Authenticated User, I want to complete my purchase so that my order can be shipped out. [FR206]|
|US210|See notifications panel|high|As an Authenticated User, I want to see all my notifications, so that I can check current orders’ information, account details and any platform changes (wishlist products, price changes…)  [FR024]
|US211|View Purchase History|high|As an Authenticated User, I want to see my purchase record, so that I can see past items I liked. [FR201]
|US507|Administer User Accounts|high|As an Administrator I want to administer users’ accounts so that I can make sure no one is violating the community guidelines. [FR041/042/608]|
|US508|Block and Unblock User Accounts|high|As an Administrator I want to block and unblock users’ accounts from making revies in order to enforce community guidelines. [FR043]|

#### 1.2. Implemented Web Resources

#### M01: Authentication and Individual Profile

|Web Resource Reference|URL|
|-|-|
|R101: Login Form|GET /login|
|R102: Login Action|POST /login|
|R103: Logout Action|POST /logout| 
|R104: Register Form |GET /register |
|R105: Register Action | POST /register|
|R108: View User Profile| GET /personalinfo|
|R110: Edit User Profile| PATCH /personalinfo|
|R113: View User Addresses| GET /addresses|
|R114: Delete Address| DELETE /delete_address/{id}|
|R115: Address Input Form| GET /add_address|
|R116: Address Input Request| POST /add_address|
|R117: Address Edit Form| GET /edit_address/{id}|
|R118: Address Edit Request| POST /edit_address/{id}|
|R123: View Orders| GET /orders | 
|R125: View Reviews |GET /reviews|
|R126: View Notifications| GET /notifications|


#### M02: Reviews and Wishlist

|Web Resource Reference|URL|
|-|-|
|R201: View Wishlist|GET /wishlist|

#### M03: Products

|Web Resource Reference|URL|
|-|-|
|R301: List Products|GET /products|
|R304: Search Product|GET /products/{searchString}|
|R305: View Product Details.|GET /products/{id}|

#### M04: Shopping Cart

|Web Resource Reference|URL|
|-|-|
|R401: View Shipping Cart|GET /shoppingcart|
|R402: Add Product to Shopping Cart|POST /shoppingcart/{id}|
|R403: Delete Product from Shopping Cart|DELETE /shoppingcart/{id}|
|R404: Checkout Form |GET /checkout |
|R405: Checkout Request |POST /checkout |

#### M05: Order

|Web Resource Reference|URL|
|-|-|
|R501: View Order|GET /order/{id}|

#### M06: Administration

|Web Resource Reference|URL|
|-|-|
|R601: View Admin Dashboard|GET /admin|
|R602: View Users|GET /users|
|R603: Block User|GET /user/{id}/block|
|R604: Unlock User|GET /user/{id}/unblock|
|R605: Edit User Profile Form|GET /user/{id}/edit|
|R606: Edit User Profile Request|Post /user/{id}/edit|

### 2. Prototype
The prototype is available at http://lbaw2124.lbaw.fe.up.pt/

Credentials:

admin user: admin@example.com/1234
regular user: user@example.com/1234

The code is available at
https://git.fe.up.pt/lbaw/lbaw2122/lbaw2124

## Revision history

GROUP2124, 02/01/2021

* António Ribeiro [up201906761@edu.fe.up.pt](mailto:up201906761@edu.fe.up.pt)
* Diogo Pereira [up201906422@edu.fe.up.pt](mailto:up201906422@edu.fe.up.pt)
* Joana Mesquita [up201907878@edu.fe.up.pt](mailto:up201907878@edu.fe.up.pt)
* Margarida Ferreira [up201905046@edu.fe.up.pt](mailto:up201905046@edu.fe.up.pt)

---

Editor:

* Margarida Ferreira [up201905046@edu.fe.up.pt](mailto:up201905046@edu.fe.up.pt)