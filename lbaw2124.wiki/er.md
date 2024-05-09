# ER: Requirements Specification Component

<span dir="">To help our community find the most suitable devices.</span>

## A1: Project Name

<span dir="">The main goal of the Feup-Tech project is the development of an information system with a web interface to support an online technology store created for the use of members of the FEUP community.</span>

<span dir="">Technology plays an integral part in our society, it helps us study, work and provides moments of relaxation, such as watching movies or playing games and to students, a personal computer is essential for college projects and assignments. Our main goal is to facilitate the access to these high-tech products, in a store platform that FEUP community members can use to buy the most suitable gadgets for their everyday work necessities, at a fair price.</span>

<span dir="">The system keeps information about various products, including comments and ratings made by users, as well as, user accounts including payment methods, a wishlist and other features that make it easier for the buyers to use the online store. On top of that it also tracks user purchases, providing useful statistics to administrators.</span>

<span dir="">There are two types of users in the system, the buyers and the administrators. The buyers can only make purchases if authenticated with a user account, otherwise they can only browse the existing products (with filters, attributes, exact match and full-text search) and create and use a shopping cart. Once authenticated, buyers can, not only, make purchases, but they also have access to a user profile from where they can manage payment methods, create and edit a wishlist, see their purchasing history and receive product recommendation. Once they buy a product, they can rate and review it as well as track or cancel any order made.</span>

<span dir="">Administrators on the other hand are not buyers since they do not possess a shopping cart, payment methods or a wishlist. Instead, they can manage an order's status, moderate buyer reviews and accounts and add, remove or edit products and product specifications on top of being privy to buyers’ purchase history and sale statistics.</span>

<span dir="">In conclusion, the platform aims to provide users with an easy to operate web interface, in which users can easily find the products they want and purchase what they need. Our focus will be in developing a design and page organization that reflects these principles.</span>

## A2: Actors and User stories

<span dir="">This artifact contains the specification of the actors and their user stories, serving as agile documentation of the project’s requirements.</span>

### 1. Actors

<span dir="">For the **FEUP-Tech** system, the actors are represented in Figure 1 and described in Table 1.</span>

![actor](uploads/bceec31cff7f92e64ca8fa2533eeb3fb/actor.png)

**<span dir="">Figure 1</span>**<span dir=""> - Feup-Tech Actors.</span>

<div>

| **<span dir="">Identifier</span>** | **<span dir="">Description</span>** |
|------------------------------------|-------------------------------------|
| <span dir="">User</span> | <span dir="">Generic user who has the power to browse and search for products as well as see the website’s static pages and add products to the shopping cart.</span> |
| <span dir="">Non-Authenticated User</span> | <span dir="">A user who can register themselves (sign-up) or sign-in.</span> |
| <span dir="">Authenticated User</span> | <span dir="">A user who has signed into the system and can make purchases (check out), manage and edit their profile from profile picture to payment methods and a wishlist, see personal notifications, receive product recommendations, and check their purchase history.</span> |
| <span dir="">Buyer</span> | <span dir="">A type of authenticated user who has purchased a product. A buyer can review the product and track or cancel an order.</span> |
| <span dir="">Review Author</span> | <span dir="">A type of authenticated user who has left a review on a product. A review author can edit and remove reviews.</span> |
| <span dir="">Administrator</span> | <span dir="">Someone who has signed into the system who has a role of moderating the overall content of the store. Their duties include supervising user profiles (blocking, removing), operating the products’ databases (reviews, quantities…), handling orders’ status and information and accessing sales statistics to coordinate supplies.</span> |
| <span dir="">Payment API</span> | <span dir="">External Payment API that can be used to make payments.</span> |
| <span dir="">Email API</span> | <span dir="">External Email API that can be used to send email notifications to users.</span> |
| <span dir="">Authentication API</span> | <span dir="">External Authentication API that can be used to register or authenticate into the system.</span> |

</div><strong><span dir="">Table 1</span></strong><span dir="">- Feup-Tech actors’ description.</span>

### 2. User Stories

<span dir="">For the **Feup-Tech** system, consider the user stories that are presented in the following sections.</span>

#### 2.1 User

<div>

| **<span dir="">Identifier</span>** | **<span dir="">Name</span>** | **<span dir="">Priority</span>** | **<span dir="">Description</span>** |
|------------------------------------|------------------------------|----------------------------------|-------------------------------------|
| <span dir="">US001</span> | <span dir="">View Product List</span> | <span dir="">high</span> | <span dir="">As a _User_, I want to be able to view the products available in the store, so that I can know what I am able to purchase. \[FR101\]</span> |
| <span dir="">US002</span> | <span dir="">Browse Product Categories</span> | <span dir="">high</span> | <span dir="">As a _User_ I want to be able to browse product categories, so that I can more easily find the type of products I am interested in. \[FR102\]</span> |
| <span dir="">US003</span> | <span dir="">View Product Details</span> | <span dir="">high</span> | <span dir="">As a _User_, I want to be able to view product details, so that I know more about a product before deciding to purchase it. \[FR103\]</span> |
| <span dir="">US004</span> | <span dir="">View Product Reviews</span> | <span dir="">high</span> | <span dir="">As a _User_, I want to be able to view product reviews, so that I can know what other people’s experience with the product was, before deciding to purchase it. \[FR104\]</span> |
| <span dir="">US005</span> | <span dir="">Add Product to Shopping Car</span> | <span dir="">high</span> | <span dir="">As a _User_, I want to be able to add products to the shopping cart, so that I can have all the products I wish to buy in one easy to access place. \[FR105\]</span> |
| <span dir="">US006</span> | <span dir="">Manage Shopping Cart</span> | <span dir="">high</span> | <span dir="">As a _User_, I want to be able to manage the shopping cart, so that I can remove any products I no longer want or change the amount of a certain product that I want. \[FR106\]</span> |
| <span dir="">US007</span> | <span dir="">Search Products</span> | <span dir="">high</span> | <span dir="">As a _User_, I want to be able to search for products, so that I can easily find a specific product I want to purchase. \[FR107\]</span> |
| <span dir="">US008</span> | <span dir="">See About Us page</span> | <span dir="">high</span> | <span dir="">As a _User_, I want to be able to go to the About Us page, to see more information about the company. \[FR061\]</span> |
| <span dir="">US009</span> | <span dir="">See Contacts</span> | <span dir="">high</span> | <span dir="">As a _User_, I want to be able to go to the contacts page, to be able to contact the company for various reasons.  \[FR063\]</span> |
| <span dir="">US010</span> | <span dir="">See FAQ</span> | <span dir="">medium</span> | <span dir="">As a _User_, I want to be able to go to the frequently asked questions, to be able to get the answer to a common question easily.</span> |

</div><strong><span dir="">Table 2</span></strong><span dir=""> - User user stories.</span>

#### 2.2 Non-Authenticated User

<div>

| **<span dir="">Identifier</span>** | **<span dir="">Name</span>** | **<span dir="">Priority</span>** | **<span dir="">Description</span>** |
|------------------------------------|------------------------------|----------------------------------|-------------------------------------|
| <span dir="">US101</span> | <span dir="">Sign-in</span> | <span dir="">high</span> | <span dir="">As a _Non-Authenticated User_, I want to authenticate into the system, so that I can buy products and manage my profile. \[FR011\]</span> |
| <span dir="">US102</span> | <span dir="">Sign-up</span> | <span dir="">high</span> | <span dir="">As a _Non-Authenticated User_, I want to register myself into the system, so that I can authenticate myself into the system.  \[FR012\]</span> |
| <span dir="">US103</span> | <span dir="">Recover Password</span> | <span dir="">high</span> | <span dir="">As a _Non-Authenticated User_, I want to be able to access my account in case I lose my password.  \[FR013\]</span> |

</div><strong><span dir="">Table 3</span></strong><span dir=""> - Non-Authenticated User user stories.</span>

#### 2.3 Authenticated User

<div>

| **<span dir="">Identifier</span>** | **<span dir="">Name</span>** | **<span dir="">Priority</span>** | **<span dir="">Description</span>** |
|------------------------------------|------------------------------|----------------------------------|-------------------------------------|
| <span dir="">US2</span>01 | <span dir="">View profile</span> | <span dir="">high</span> | <span dir="">As an _Authenticated User,_ I want to be able to view my public and private profile settings and information, so that I know what information I am currently making publicly available.  \[FR021\]</span> |
| <span dir="">US20</span>2 | <span dir="">Manage profile</span> | <span dir="">high</span> | <span dir="">As an </span>_Authenticated User<span dir="">,</span>_<span dir=""> I want to manage all my account details (such as email, password, address, picture, preference settings, …) so that I can change them if they are no longer up to date.  \[FR022/23\]</span> |
| <span dir="">US20</span>3 | <span dir="">Delete Account</span> | <span dir="">high</span> | <span dir="">As an </span>_Authenticated <span dir="">user</span>_<span dir="">, I want to delete all my account information, in accordance with the platform rules, because I no longer want to make purchases.  \[FR014\]</span> |
| <span dir="">US20</span>4 | <span dir="">Log Out</span> | <span dir="">high</span> | <span dir="">As an </span>_Authenticated User_<span dir="">, I want to be able to log out of my account, so that other people using the device can’t use it.</span> |
| <span dir="">US20</span>5 | <span dir="">Go to checkout page</span> | <span dir="">high</span> | <span dir="">As an </span>_Authenticated <span dir="">User</span>_<span dir="">, I want to go to my checkout page, so that I can place an order.</span> |
| <span dir="">US20</span>6 | <span dir="">Complete purchase/Checkout</span> | <span dir="">high</span> | <span dir="">As an </span>_Authenticated <span dir="">User</span>_<span dir="">, I want to complete my purchase so that my order can be shipped out. \[FR206\]</span> |
| <span dir="">US20</span>7 | <span dir="">Leave a product rating and review</span> | <span dir="">high</span> | <span dir="">As an </span>_Authenticated <span dir="">User</span>_<span dir="">, I want to leave ratings and reviews, so that other people can make more thoughtfully planned purchases. \[FR205\]</span> |
| <span dir="">US20</span>8 | <span dir="">Add product to the wishlist</span> | <span dir="">high</span> | <span dir="">As an </span>_Authenticated <span dir="">User</span>_<span dir="">, I want to add a product to my wishlist, so that I can save it for a future purchase. \[FR202\]</span> |
| <span dir="">US20</span>9 | <span dir="">Manage wishlist</span> | <span dir="">high</span> | <span dir="">As an _Authenticated User_, I want to manage my wishlist so that I can remove products I no longer desire. \[FR203\]</span> |
| <span dir="">US2</span>10 | <span dir="">See notifications panel</span> | <span dir="">high</span> | <span dir="">As an _Authenticated User_, I want to see all my notifications, so that I can check current orders’ information, account details and any platform changes (wishlist products, price changes…)  \[FR024\]</span> |
| <span dir="">US21</span>1 | <span dir="">View Purchase History</span> | <span dir="">high</span> | <span dir="">As an _Authenticated User_, I want to see my purchase record, so that I can see past items I liked. \[FR201\]</span> |
| <span dir="">US212</span> | <span dir="">See order information (before placing order)</span> | <span dir="">medium</span> | <span dir="">As an _Authenticated User_, I want to see my order details (delivery address, discounts applied, price…), so that I can review them.</span> |
| <span dir="">US213</span> | <span dir="">Edit order information (before placing order)</span> | <span dir="">medium</span> | <span dir="">As an _Authenticated User_, I want to edit certain order specifications (addresses, quantities...), so that I can change invalid information.</span> |
| <span dir="">US214</span> | <span dir="">Edit payment details</span> | <span dir="">medium</span> | <span dir="">As an _Authenticated User_, I want to edit my payment and billing details so that I can change them if they become invalid.</span> |
| <span dir="">US215</span> | View the personal recommendations panel | <span dir="">medium</span> | <span dir="">As an _Authenticated User_, I want to see the recommendation panel, so that I can be informed of products that might interest me. \[FR212\]</span> |
| <span dir="">US216</span> | <span dir="">Manage Multiple Payment Methods</span> | <span dir="">medium</span> | <span dir="">As an _Authenticated User_, I want to manage my payment methods, so that I can have multiple options on checkout. \[FR204\]</span> |
| <span dir="">US217</span> | <span dir="">Report Review</span> | <span dir="">medium</span> | <span dir="">As an _Authenticated User_, I want to report any review that (according to my perspective) violates the store rules and agreements, to be further evaluated by an administrator. \[FR207\]</span> |
| <span dir="">US218</span> | <span dir="">Manage Account Credits</span> | <span dir="">low</span> | <span dir="">As an _Authenticated User_, I want to manage account credits, so that I can add credits to use in the future. \[FR211\]</span> |
| <span dir="">US219</span> | <span dir="">Appeal for unblock</span> | <span dir="">low</span> | <span dir="">As an _Authenticated User_, I want to appeal for unblock, so that I can continue to write reviews. \[FR025\]</span> |

</div><strong><span dir="">Table 4</span></strong><span dir=""> - Authenticated User user stories.</span>

#### 2.4 Buyer

<div>

| **<span dir="">Identifier</span>** | **<span dir="">Name</span>** | **<span dir="">Priority</span>** | **<span dir="">Description</span>** |
|------------------------------------|------------------------------|----------------------------------|-------------------------------------|
| <span dir="">US301</span> | <span dir="">Review Purchased Product</span> | <span dir="">high</span> | <span dir="">As a _Buyer_ I want to review a purchased product so that other users can make a more well-informed decision while buying. \[FR205/301\]</span> |
| <span dir="">US302</span> | <span dir="">Track Order</span> | <span dir="">high</span> | <span dir="">As a _Buyer_ I want to track my orders in order to know when my product will arrive and where it is at the current moment. \[FR402\]</span> |
| <span dir="">US303</span> <span dir=""> </span> | <span dir="">Cancel Order</span> | <span dir="">high</span> | <span dir="">As a _Buyer_ I want to be able to cancel an order in case I regret making it or made it by accident. \[FR403\]</span> |
| <span dir="">US304</span> | <span dir="">Edit Order</span> | <span dir="">low</span> | <span dir="">As a _Buyer_ I want to be able to, for a limited time, edit an order’s details in case I input wrong mailing or billing information (delivery information can be altered, depending on order status).</span> |

</div><strong><span dir="">Table 5</span></strong><span dir=""> - Buyer user stories.</span>

#### 2.5 Review Author

<div>

| **<span dir="">Identifier</span>** | **<span dir="">Name</span>** | **<span dir="">Priority</span>** | **<span dir="">Description</span>** |
|------------------------------------|------------------------------|----------------------------------|-------------------------------------|
| <span dir="">US401</span> | <span dir="">Edit Review</span> | <span dir="">high</span> | <span dir="">As a _Review Author_ I want to edit my previous reviews so that I can fix spelling/</span> <span dir="">grammatical errors and change the provided information if it’s not up to date. \[FR301\]</span> |
| <span dir="">US402</span> | <span dir="">Delete Review</span> | <span dir="">high</span> | <span dir="">As a _Review Author_ I want to delete my previous reviews in case they do not stand anymore or if I don’t want other people seeing said reviews. \[FR302\]</span> |

</div><strong><span dir="">Table 6</span></strong><span dir=""> - Review Author user stories.</span>

#### 2.6 Administrator

<div>

| **<span dir="">Identifier</span>** | **<span dir="">Name</span>** | **<span dir="">Priority</span>** | **<span dir="">Description</span>** |
|------------------------------------|------------------------------|----------------------------------|-------------------------------------|
| <span dir="">US501</span> | <span dir="">Add Product</span> | <span dir="">high</span> | <span dir="">As an _Administrator_ I want to add a product to the website so that it is available for users to buy it. \[FR601\]</span> |
| <span dir="">US502</span> | <span dir="">Manage Products’ Information</span> | <span dir="">high</span> | <span dir="">As an _Administrator_ I want to manage products’ information so that I can keep it updated.  \[FR602/605\]</span> |
| <span dir="">US503</span> | <span dir="">Manage Products’ Stock</span> | <span dir="">high</span> | <span dir="">As an _Administrator_ I want to manage products’ stock in order to keep it updated.  \[FR603\]</span> |
| <span dir="">US504</span> | <span dir="">Manage Product Categories</span> | <span dir="">high</span> | <span dir="">As an _Administrator_ I want to manage product categories so that I can add or remove categories of products.  \[FR604\]</span> |
| <span dir="">US505</span> | <span dir="">View Users’ Purchase History</span> | <span dir="">high</span> | <span dir="">As an _Administrator_ I want to view the users’ purchase history so that I can improve the experience I offer them.</span> <span dir=""> \[FR606\]</span> |
| <span dir="">US506</span> | <span dir="">Manage Order Status</span> | <span dir="">high</span> | <span dir="">As an Administrator I want to manage the orders’ status so that users may know the situation of their purchase.  \[FR607\]</span> |
| <span dir="">US507</span> | <span dir="">Administer User Accounts</span> | <span dir="">high</span> | <span dir="">As an _Administrator_ I want to administer users’ accounts so that I can make sure no one is violating the community guidelines. \[FR041/042/608\]</span> |
| <span dir="">US508</span> | <span dir="">Block and Unblock User Accounts</span> | <span dir="">high</span> | <span dir="">As an _Administrator_ I want to block and unblock users’ accounts from making revies in order to enforce community guidelines. \[FR043\]</span> |
| <span dir="">US509</span> | <span dir="">Delete User Account</span> | <span dir="">high</span> | <span dir="">As an _Administrator_ I want to delete users’ accounts in order to eliminate bot and spam accounts.  \[FR044\]</span> |

</div><strong><span dir="">Table 7</span></strong><span dir=""> - Administrator user stories.</span>

### 3. Supplementary Requirements

<span dir="">This section contains business rules, technical requirements and other non-functional requirements on the project.</span>

#### 3.1 Business rules

<div>

| **<span dir="">Identifier</span>** | **<span dir="">Name</span>** | **<span dir="">Description</span>** |
|------------------------------------|------------------------------|-------------------------------------|
| <span dir="">BR01</span> | <span dir="">Account deletion</span> | <span dir="">Upon account deletion, shared user data (e.g. comments, reviews, likes) is kept but is made anonymous. \[BR011\]</span> |
| <span dir="">BR02</span> | <span dir="">Administrators accounts independency</span> | <span dir="">Administrators can’t access user’s features that relate to purchases (checkout, manage their own shopping lists...).</span> |
| <span dir="">BR03</span> | <span dir="">Cancelling and refunds</span> | <span dir="">Order cancellation and refunds are available until shipment. </span> |
| <span dir="">BR04</span> | <span dir="">Changing order information (after the order is placed)</span> | <span dir="">An order’s billing method can be altered until payment and an order’s address can be edited until the order is shipped out.</span> |
| <span dir="">BR05</span> | <span dir="">Available stock</span> | <span dir="">To complete a purchase, the product in question must have available stock.</span> |
| <span dir="">BR06</span> | <span dir="">Unique review</span> | <span dir="">An authorized user can only make one review/rating per product.</span> |
| <span dir="">BR07</span> | <span dir="">Review rating</span> | <span dir="">A review must have a mandatory rating.</span> |

</div><strong><span dir="">Table 8</span></strong><span dir="">- Feup-Tech business rules.</span>

#### 3.2. Technical requirements

<div>

| **<span dir="">Identifier</span>** | **<span dir="">Name</span>** | **<span dir="">Description</span>** |
|------------------------------------|------------------------------|-------------------------------------|
| <span dir="">TR01</span> | <span dir="">Performance</span> | <span dir="">The system should have response times shorter than 2s to ensure the user's attention</span> |
| **<span dir="">TR02</span>** | **<span dir="">Robustness</span>** | **<span dir="">The system must be prepared to handle and continue operating when runtime errors occur.</span>**   **<span dir="">It’s important that Feup-Tech can continue functioning as expected after encountering a runtime error to avoid unnecessary confusion and enhance the user experience.</span>** |
| <span dir="">TR03</span> | <span dir="">Scalability</span> | <span dir="">The system must be prepared to deal with the growth in the number of users and their actions.</span> |
| **<span dir="">TR04</span>** | **<span dir="">Accessibility</span>** | **<span dir="">The system must ensure that everyone can access the pages, regardless of whether they have any handicap or not, or the Web browser they use.</span>**   **<span dir="">In order to cater to users who have any type of handicap, our platform must ensure the design model is clear and that the necessary measures are taken to implement accessibility features (e.g. facilitate screen reading protocols) that help these customers browse the store.</span>** |
| <span dir="">TR05</span> | <span dir="">Availability</span> | <span dir="">The system must be available 99 percent of the time in each 24-hour period</span> |
| **<span dir="">TR06</span>** | **<span dir="">Usability</span>** | **<span dir="">The system should be simple and easy to use.</span>**   **<span dir="">The FeupTech system is designed in a way that ensures that any user in any context can use it intuitively, regardless of their age or technical experience.</span>** |
| <span dir="">TR07</span> | <span dir="">Web Application</span> | <span dir="">The system should be implemented as a web application with dynamic pages (HTML5, JavaScript, CSS3 and PHP).</span> |
| <span dir="">TR08</span> | <span dir="">Portability</span> | <span dir="">The server-side system should work across multiple platforms (Linux, Mac OS, etc.).</span> |
| <span dir="">TR09</span> | <span dir="">Database</span> | <span dir="">The PostgreSQL database management system must be used, with a version of 11 or higher.</span> |
| <span dir="">TR10</span> | <span dir="">Security</span> | <span dir="">The system shall protect information from unauthorized access with an authentication and verification system.</span> |
| <span dir="">TR11</span> | <span dir="">Ethics</span> | <span dir="">The system must respect the ethical principles in software development (for example, personal user details, or usage data, should not be collected nor shared without full acknowledgement and authorization from its owner)</span> |

</div><strong><span dir="">Table 9</span></strong><span dir="">- Feup-Tech technical requirements.</span>

#### 3.3. Restrictions

<div>

| **<span dir="">Identifier</span>** | **<span dir="">Name</span>** | **<span dir="">Description</span>** |
|------------------------------------|------------------------------|-------------------------------------|
| <span dir="">C01</span> | <span dir="">Deadline</span> | <span dir="">The system should be ready by the 17th of January so that sales can start at the beginning of the second semester.</span> |

</div><strong><span dir="">Table 10</span></strong><span dir="">- Feup-Tech project restrictions.</span>

## A3: Information Architecture

<span dir="">This artefact presents a brief overview of the information architecture of the system to be developed and includes two elements, a sitemap, defining how the information is organized in pages and a set of wireframes, defining the functionality and the content for two important pages.</span>

### 1. Sitemap

<span dir="">This sitemap represents the overall scheme of the **Feup-Tech** platform. It is divided in 5 main sections:</span>

<span dir="">●       Homepage,</span>

<span dir="">●       Static Pages (provide general information about the store),</span>

<span dir="">●       User pages (details available to an authenticated user),</span>

<span dir="">●       Shopping cart pages (access to order and checkout stages),</span>

<span dir="">●       Product Library pages (connect to the products’ database),</span>

<span dir="">●       Administrator pages (dashboard with privileged actions for product and user management).</span>

<span dir=""> </span>![sitemap](uploads/b4bcf90ceadc3e519f7125dd22a917cc/sitemap.png)

**<span dir="">Figure 2 -</span>**<span dir=""> Feup-Tech sitemap.</span>

### 2. Wireframes

<span dir="">The wireframes presented below describe the layout of the **Homepage** (UI01) and the **Product details page** (UI20), combining the view of both the Administrator and a non-authenticated user.</span>

#### UI01: Homepage

![homepage](uploads/46222b1933b0f85ccb94e4e82d63da79/homepage.png)

**<span dir="">Figure 3 -</span>**<span dir=""> Homepage (UI01) wireframe.</span>

#### UI20: Product Details Page

![product_page](uploads/14b2217a9857bb02c58cd1ec2aa9b0f8/product_page.png)

**<span dir="">Figure 4 -</span>**<span dir=""> Product Details (UI20) wireframe.</span>

---

### Revision history

---

GROUP2124, 08/11/2021

* António Ribeiro [up201906761@edu.fe.up.pt](mailto:up201906761@edu.fe.up.pt)
* Diogo Pereira [up201906422@edu.fe.up.pt](mailto:up201906422@edu.fe.up.pt)
* Joana Mesquita [up201907878@edu.fe.up.pt](mailto:up201907878@edu.fe.up.pt)
* Margarida Ferreira [up201905046@edu.fe.up.pt](mailto:up201905046@edu.fe.up.pt)

---

Editor:

* Joana Mesquita [up201907878@edu.fe.up.pt](mailto:up201907878@edu.fe.up.pt)